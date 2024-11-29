<?php

require_once 'Database.php';

class Product {
    private ?int $id;
    private string $name;
    private array $photos;
    private int $price;
    private string $description;
    private int $quantity;
    private ?int $category_id;
    private ?string $created_at;
    private ?string $updated_at;

    public function __construct(
        ?int $id = null,
        string $name = '',
        array $photos = [],
        int $price = 0,
        string $description = '',
        int $quantity = 0,
        ?int $category_id = null,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->photos = $photos;
        $this->price = $price;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->category_id = $category_id;
        $this->created_at = $created_at ?? date('Y-m-d H:i:s');
        $this->updated_at = $updated_at ?? date('Y-m-d H:i:s');
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPhotos(): array {
        return $this->photos;
    }

    public function getPrice(): int {
        return $this->price;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getCategoryId(): ?int {
        return $this->category_id;
    }

    public function getCreatedAt(): ?string {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string {
        return $this->updated_at;
    }

    // Setters
    public function setName(string $name): void {
        $this->name = $name;
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
    }

    public function setPhotos(array $photos): void {
        $this->photos = $photos;
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
    }

    public function setPrice(int $price): void {
        $this->price = $price;
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
    }

    public function setDescription(string $description): void {
        $this->description = $description;
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
    }

    public function setCategoryId(?int $category_id): void {
        $this->category_id = $category_id;
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
    }

    private function setUpdatedAt(?string $updated_at): void {
        $this->updated_at = $updated_at;
    }

    // Méthodes CRUD
    public function create(): bool {
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
                $db->commit();
                return true;
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
        if (!$this->id) {
            return false;
        }

        try {
            $db = Database::getInstance();
            
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

            return $query->execute([
                'id' => $this->id,
                'name' => $this->name,
                'photos' => json_encode($this->photos),
                'price' => $this->price,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'category_id' => $this->category_id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function findOneById(int $id): ?self {
        try {
            $db = Database::getInstance();
            $query = $db->prepare('SELECT * FROM product WHERE id = :id');
            $query->execute(['id' => $id]);
            
            $data = $query->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                return null;
            }

            return new self(
                $data['id'],
                $data['name'],
                json_decode($data['photos'], true) ?? [],
                $data['price'],
                $data['description'],
                $data['quantity'],
                $data['category_id'],
                $data['created_at'],
                $data['updated_at']
            );

        } catch (PDOException $e) {
            return null;
        }
    }

    public static function findAll(): array {
        try {
            $db = Database::getInstance();
            $query = $db->query('SELECT * FROM product');
            
            $products = [];
            while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
                $products[] = new self(
                    $data['id'],
                    $data['name'],
                    json_decode($data['photos'], true) ?? [],
                    $data['price'],
                    $data['description'],
                    $data['quantity'],
                    $data['category_id'],
                    $data['created_at'],
                    $data['updated_at']
                );
            }

            return $products;

        } catch (PDOException $e) {
            return [];
        }
    }
} 