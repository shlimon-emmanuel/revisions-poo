<?php

require_once 'Product.php';
require_once 'Database.php';
require_once 'AbstractProduct.php';

class Clothing extends AbstractProduct {
    private string $size;
    private string $color;
    private string $type;
    private int $material_fee;

    public function __construct(
        ?int $id = null,
        string $name = '',
        array $photos = [],
        int $price = 0,
        string $description = '',
        int $quantity = 0,
        ?int $category_id = null,
        string $size = '',
        string $color = '',
        string $type = '',
        int $material_fee = 0
    ) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $category_id);
        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
    }

    // Getters
    public function getSize(): string {
        return $this->size;
    }

    public function getColor(): string {
        return $this->color;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getMaterialFee(): int {
        return $this->material_fee;
    }

    // Setters
    public function setColor(string $color): void {
        $this->color = $color;
    }

    public static function findOneById(int $id): ?static {
        try {
            $db = Database::getInstance();
            
            $query = $db->prepare('
                SELECT p.*, c.size, c.color, c.type, c.material_fee
                FROM product p
                JOIN clothing c ON c.product_id = p.id
                WHERE p.id = :id
            ');
            
            $query->execute(['id' => $id]);
            $data = $query->fetch();
            
            if ($data) {
                return new static(
                    $data['id'],
                    $data['name'],
                    json_decode($data['photos'], true),
                    $data['price'],
                    $data['description'],
                    $data['quantity'],
                    $data['category_id'],
                    $data['size'],
                    $data['color'],
                    $data['type'],
                    $data['material_fee']
                );
            }
            
            return null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function findAll(): array {
        try {
            $db = Database::getInstance();
            
            $query = $db->prepare('
                SELECT p.*, c.size, c.color, c.type, c.material_fee
                FROM product p
                JOIN clothing c ON c.product_id = p.id
            ');
            
            $query->execute();
            $clothings = [];
            
            while ($data = $query->fetch()) {
                $clothings[] = new static(
                    $data['id'],
                    $data['name'],
                    json_decode($data['photos'], true),
                    $data['price'],
                    $data['description'],
                    $data['quantity'],
                    $data['category_id'],
                    $data['size'],
                    $data['color'],
                    $data['type'],
                    $data['material_fee']
                );
            }
            
            return $clothings;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function create(): static|false {
        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            $query = $db->prepare('
                INSERT INTO product (name, photos, price, description, quantity, category_id)
                VALUES (:name, :photos, :price, :description, :quantity, :category_id)
            ');

            $result = $query->execute([
                'name' => $this->name,
                'photos' => json_encode($this->photos),
                'price' => $this->price,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'category_id' => $this->category_id
            ]);

            if ($result) {
                $this->id = (int) $db->lastInsertId();

                $query = $db->prepare('
                    INSERT INTO clothing (product_id, size, color, type, material_fee)
                    VALUES (:product_id, :size, :color, :type, :material_fee)
                ');

                $result = $query->execute([
                    'product_id' => $this->id,
                    'size' => $this->size,
                    'color' => $this->color,
                    'type' => $this->type,
                    'material_fee' => $this->material_fee
                ]);

                if ($result) {
                    $db->commit();
                    return $this;
                }
            }

            $db->rollBack();
            return false;
        } catch (PDOException $e) {
            $db->rollBack();
            return false;
        }
    }

    public function update(): bool {
        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            // Mise à jour de la table product
            $query = $db->prepare('
                UPDATE product 
                SET name = :name,
                    photos = :photos,
                    price = :price,
                    description = :description,
                    quantity = :quantity,
                    category_id = :category_id
                WHERE id = :id
            ');

            $result = $query->execute([
                'id' => $this->id,
                'name' => $this->name,
                'photos' => json_encode($this->photos),
                'price' => $this->price,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'category_id' => $this->category_id
            ]);

            if ($result) {
                // Mise à jour de la table clothing
                $query = $db->prepare('
                    UPDATE clothing 
                    SET size = :size,
                        color = :color,
                        type = :type,
                        material_fee = :material_fee
                    WHERE product_id = :product_id
                ');

                $result = $query->execute([
                    'product_id' => $this->id,
                    'size' => $this->size,
                    'color' => $this->color,
                    'type' => $this->type,
                    'material_fee' => $this->material_fee
                ]);

                if ($result) {
                    $db->commit();
                    return true;
                }
            }

            $db->rollBack();
            return false;

        } catch (PDOException $e) {
            $db->rollBack();
            return false;
        }
    }

    public function addStocks(int $quantity): bool {
        if ($quantity < 0) {
            throw new InvalidArgumentException("La quantité doit être positive");
        }

        try {
            $db = Database::getInstance();
            $query = $db->prepare('
                UPDATE product 
                SET quantity = quantity + :quantity 
                WHERE id = :id
            ');

            $result = $query->execute([
                'id' => $this->id,
                'quantity' => $quantity
            ]);

            if ($result) {
                $this->quantity += $quantity;
                return true;
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function removeStocks(int $quantity): bool {
        if ($quantity < 0) {
            throw new InvalidArgumentException("La quantité doit être positive");
        }

        if ($quantity > $this->quantity) {
            throw new OutOfBoundsException("Stock insuffisant");
        }

        try {
            $db = Database::getInstance();
            $query = $db->prepare('
                UPDATE product 
                SET quantity = quantity - :quantity 
                WHERE id = :id AND quantity >= :quantity
            ');

            $result = $query->execute([
                'id' => $this->id,
                'quantity' => $quantity
            ]);

            if ($result && $query->rowCount() > 0) {
                $this->quantity -= $quantity;
                return true;
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function isInStock(): bool {
        return $this->quantity > 0;
    }
} 