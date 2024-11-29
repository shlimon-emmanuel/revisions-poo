<?php

require_once 'Database.php';

abstract class Product {
    protected ?int $id;
    protected string $name;
    protected array $photos;
    protected int $price;
    protected string $description;
    protected int $quantity;
    protected ?int $category_id;
    protected ?string $created_at;
    protected ?string $updated_at;

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
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
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
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function setPhotos(array $photos): void {
        $this->photos = $photos;
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function setPrice(int $price): void {
        $this->price = $price;
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function setDescription(string $description): void {
        $this->description = $description;
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function setCategoryId(?int $category_id): void {
        $this->category_id = $category_id;
        $this->updated_at = date('Y-m-d H:i:s');
    }

    // Méthodes abstraites que les classes enfants devront implémenter
    abstract public function create(): bool;
    abstract public function update(): bool;
} 