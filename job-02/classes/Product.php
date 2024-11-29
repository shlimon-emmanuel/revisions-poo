<?php

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
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setPhotos(array $photos): void {
        $this->photos = $photos;
    }

    public function setPrice(int $price): void {
        $this->price = $price;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function setCategoryId(?int $category_id): void {
        $this->category_id = $category_id;
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
    }

    public function setCreatedAt(?string $created_at): void {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt(?string $updated_at): void {
        $this->updated_at = $updated_at;
    }

    // Méthodes create et update
    public function create(): bool {
        try {
            $db = Database::getInstance();
            $query = $db->prepare('
                INSERT INTO product (
                    name, photos, price, description, 
                    quantity, category_id, created_at, updated_at
                ) VALUES (
                    :name, :photos, :price, :description, 
                    :quantity, :category_id, :created_at, :updated_at
                )
            ');

            return $query->execute([
                'name' => $this->name,
                'photos' => json_encode($this->photos),
                'price' => $this->price,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'category_id' => $this->category_id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ]);

        } catch (PDOException $e) {
            return false;
        }
    }

    public function update(): bool {
        try {
            $this->setUpdatedAt(date('Y-m-d H:i:s'));
            
            $db = Database::getInstance();
            $query = $db->prepare('
                UPDATE product 
                SET name = :name,
                    photos = :photos,
                    price = :price,
                    description = :description,
                    quantity = :quantity,
                    category_id = :category_id,
                    updated_at = :updated_at
                WHERE id = :id
            ');

            return $query->execute([
                'id' => $this->id,
                'name' => $this->name,
                'photos' => json_encode($this->photos),
                'price' => $this->price,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'category_id' => $this->category_id,
                'updated_at' => $this->updated_at
            ]);

        } catch (PDOException $e) {
            return false;
        }
    }
} 