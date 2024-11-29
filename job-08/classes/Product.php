<?php

abstract class Product {
    protected ?int $id;
    protected string $name;
    protected array $photos;
    protected int $price;
    protected string $description;
    protected int $quantity;
    protected ?int $category_id;

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

    // Méthodes de gestion du stock
    public function addStock(int $quantity): bool {
        if ($quantity < 0) {
            return false;
        }
        $this->quantity += $quantity;
        return true;
    }

    public function removeStock(int $quantity): bool {
        if ($quantity < 0 || $quantity > $this->quantity) {
            return false;
        }
        $this->quantity -= $quantity;
        return true;
    }

    public function isInStock(): bool {
        return $this->quantity > 0;
    }

    // Méthodes abstraites
    abstract public function create(): bool;
    abstract public function update(): bool;
} 