<?php

require_once 'Database.php';
require_once 'Category.php';

class Product {
    private ?int $id;
    private string $name;
    private array $photos;
    private int $price;
    private string $description;
    private int $quantity;
    private ?int $category_id;

    public function __construct(
        ?int $id = null,
        string $name = '',
        array $photos = [],
        int $price = 0,
        string $description = '',
        int $quantity = 0,
        ?int $category_id = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->photos = $photos;
        $this->price = $price;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->category_id = $category_id;
    }

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

    public function getCategory(): ?Category {
        if ($this->category_id === null) {
            return null;
        }

        try {
            $db = Database::getInstance();
            $query = $db->prepare('SELECT * FROM category WHERE id = :id');
            $query->execute(['id' => $this->category_id]);
            $data = $query->fetch();

            if ($data) {
                return Category::fromArray($data);
            }

            return null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['name'] ?? '',
            isset($data['photos']) ? json_decode($data['photos'], true) : [],
            $data['price'] ?? 0,
            $data['description'] ?? '',
            $data['quantity'] ?? 0,
            $data['category_id'] ?? null
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'photos' => json_encode($this->photos),
            'price' => $this->price,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'category_id' => $this->category_id
        ];
    }
} 