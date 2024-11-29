<?php

require_once 'Database.php';
require_once __DIR__ . '/../interfaces/EntityInterface.php';
require_once __DIR__ . '/../interfaces/StockableInterface.php';

abstract class Product implements EntityInterface, StockableInterface {
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

    // Implémentation de EntityInterface
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    // Implémentation de StockableInterface
    public function addStock(int $quantity): void {
        if ($quantity > 0) {
            $this->quantity += $quantity;
            $this->update();
        }
    }

    public function removeStock(int $quantity): void {
        if ($quantity > 0 && $quantity <= $this->quantity) {
            $this->quantity -= $quantity;
            $this->update();
        }
    }

    public function isInStock(): bool {
        return $this->quantity > 0;
    }

    // Autres getters
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

    // Méthodes abstraites que les classes enfants doivent implémenter
    abstract public function create(): bool;
    abstract public function update(): bool;
} 