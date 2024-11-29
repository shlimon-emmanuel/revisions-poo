<?php

class Category {
    private ?int $id;
    private string $name;
    private string $description;

    public function __construct(
        ?int $id = null,
        string $name = '',
        string $description = ''
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['name'] ?? '',
            $data['description'] ?? ''
        );
    }
} 