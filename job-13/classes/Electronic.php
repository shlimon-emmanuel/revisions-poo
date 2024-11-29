<?php

require_once 'Product.php';
require_once 'Database.php';

class Electronic extends Product {
    private string $brand;
    private int $warranty_fee;

    public function __construct(
        ?int $id = null,
        string $name = '',
        array $photos = [],
        int $price = 0,
        string $description = '',
        int $quantity = 0,
        ?int $category_id = null,
        string $brand = '',
        int $warranty_fee = 0
    ) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $category_id);
        $this->brand = $brand;
        $this->warranty_fee = $warranty_fee;
    }

    // Getters
    public function getBrand(): string {
        return $this->brand;
    }

    public function getWarrantyFee(): int {
        return $this->warranty_fee;
    }

    public static function findOneById(int $id): ?static {
        try {
            $db = Database::getInstance();
            
            $query = $db->prepare('
                SELECT p.*, e.brand, e.warranty_fee
                FROM product p
                JOIN electronic e ON e.product_id = p.id
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
                    $data['brand'],
                    $data['warranty_fee']
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
                SELECT p.*, e.brand, e.warranty_fee
                FROM product p
                JOIN electronic e ON e.product_id = p.id
            ');
            
            $query->execute();
            $electronics = [];
            
            while ($data = $query->fetch()) {
                $electronics[] = new static(
                    $data['id'],
                    $data['name'],
                    json_decode($data['photos'], true),
                    $data['price'],
                    $data['description'],
                    $data['quantity'],
                    $data['category_id'],
                    $data['brand'],
                    $data['warranty_fee']
                );
            }
            
            return $electronics;
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
                    INSERT INTO electronic (product_id, brand, warranty_fee)
                    VALUES (:product_id, :brand, :warranty_fee)
                ');

                $result = $query->execute([
                    'product_id' => $this->id,
                    'brand' => $this->brand,
                    'warranty_fee' => $this->warranty_fee
                ]);

                if ($result) {
                    $db->commit();
                    return $this;
                }
            }

            $db->rollBack();
            return false;
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }

    public function update(): bool {
        try {
            $db = Database::getInstance();
            $db->beginTransaction();

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
                $query = $db->prepare('
                    UPDATE electronic 
                    SET brand = :brand,
                        warranty_fee = :warranty_fee
                    WHERE product_id = :product_id
                ');

                $result = $query->execute([
                    'product_id' => $this->id,
                    'brand' => $this->brand,
                    'warranty_fee' => $this->warranty_fee
                ]);

                if ($result) {
                    $db->commit();
                    return true;
                }
            }

            $db->rollBack();
            return false;
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }
} 