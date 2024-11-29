<?php

require_once 'Database.php';
require_once 'Product.php';

class Category {
    private ?int $id;
    private string $name;
    private string $description;
    private ?string $created_at;
    private ?string $updated_at;
    private array $products = [];

    public function __construct(
        ?int $id = null,
        string $name = '',
        string $description = '',
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
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

    public function getDescription(): string {
        return $this->description;
    }

    public function getCreatedAt(): ?string {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string {
        return $this->updated_at;
    }

    public function getProducts(): array {
        return $this->products;
    }

    // Setters
    public function setName(string $name): void {
        $this->name = $name;
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
    }

    public function setDescription(string $description): void {
        $this->description = $description;
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
    }

    private function setUpdatedAt(?string $updated_at): void {
        $this->updated_at = $updated_at;
    }

    // Méthodes CRUD
    public function create(): bool {
        try {
            $db = Database::getInstance();
            $query = $db->prepare('
                INSERT INTO category (name, description, created_at, updated_at)
                VALUES (:name, :description, :created_at, :updated_at)
            ');

            $result = $query->execute([
                'name' => $this->name,
                'description' => $this->description,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ]);

            if ($result) {
                $this->id = (int) $db->lastInsertId();
            }

            return $result;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function update(): bool {
        if ($this->id === null) {
            return false;
        }

        try {
            $db = Database::getInstance();
            $query = $db->prepare('
                UPDATE category 
                SET name = :name,
                    description = :description,
                    updated_at = :updated_at
                WHERE id = :id
            ');

            return $query->execute([
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description,
                'updated_at' => date('Y-m-d H:i:s')
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
            
            $data = $query->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                return null;
            }

            return new self(
                $data['id'],
                $data['name'],
                $data['description'],
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
            $query = $db->query('SELECT * FROM category');
            
            $categories = [];
            while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
                $categories[] = new self(
                    $data['id'],
                    $data['name'],
                    $data['description'],
                    $data['created_at'],
                    $data['updated_at']
                );
            }

            return $categories;

        } catch (PDOException $e) {
            return [];
        }
    }

    public function getProductsFromCategory(): array {
        try {
            $db = Database::getInstance();
            $query = $db->prepare('SELECT * FROM product WHERE category_id = :category_id');
            $query->execute(['category_id' => $this->id]);
            
            $this->products = [];
            while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
                $this->products[] = new Product(
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

            return $this->products;

        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Compte le nombre de produits dans la catégorie
     */
    public function countProducts(): int {
        try {
            $db = Database::getInstance();
            $query = $db->prepare('
                SELECT COUNT(*) as count 
                FROM product 
                WHERE category_id = :category_id
            ');
            
            $query->execute(['category_id' => $this->id]);
            $result = $query->fetch();
            
            return (int) $result['count'];

        } catch (PDOException $e) {
            return 0;
        }
    }
} 