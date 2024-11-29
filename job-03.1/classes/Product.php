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

    // Méthodes statiques
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

    // ... autres méthodes (create, update, etc.)
} 