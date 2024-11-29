<?php

namespace App;

use PDO;
use PDOException;
use App\Interface\EntityInterface;
use App\Database;

class Category implements EntityInterface {
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

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    // Setters
    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function create(): bool {
        try {
            $db = Database::getInstance();
            
            $query = $db->prepare('
                INSERT INTO category (name, description)
                VALUES (:name, :description)
            ');
            
            $result = $query->execute([
                'name' => $this->name,
                'description' => $this->description
            ]);

            if ($result) {
                $this->id = (int) $db->lastInsertId();
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
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
                UPDATE category 
                SET name = :name,
                    description = :description
                WHERE id = :id
            ');
            
            return $query->execute([
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function findOneById(int $id): ?self {
        try {
            $db = Database::getInstance();
            
            $query = $db->prepare('SELECT * FROM category WHERE id = :id');
            $query->execute(['id' => $id]);
            $data = $query->fetch();
            
            if ($data) {
                return new self(
                    $data['id'],
                    $data['name'],
                    $data['description']
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
            
            $query = $db->prepare('SELECT * FROM category');
            $query->execute();
            $categories = [];
            
            while ($data = $query->fetch()) {
                $categories[] = new self(
                    $data['id'],
                    $data['name'],
                    $data['description']
                );
            }
            
            return $categories;
        } catch (PDOException $e) {
            return [];
        }
    }
} 