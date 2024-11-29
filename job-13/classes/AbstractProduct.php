<?php

abstract class AbstractProduct {
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

    abstract public function addStocks(int $quantity): bool;
    abstract public function removeStocks(int $quantity): bool;
    abstract public function isInStock(): bool;
    abstract public static function findOneById(int $id): ?static;
    abstract public static function findAll(): array;
    abstract public function create(): static|false;
    abstract public function update(): bool;

    // Getters et setters
    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    // ... autres getters et setters ...
} 