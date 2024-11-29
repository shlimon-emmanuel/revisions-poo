<?php

require_once 'Database.php';
require_once __DIR__ . '/../interfaces/EntityInterface.php';

class Category implements EntityInterface {
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

    // Implémentation de EntityInterface
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    // Autres getters
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

    // Méthode CRUD create()
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
                return true;
            }

            return false;

        } catch (PDOException $e) {
            return false;
        }
    }

    // Méthodes de récupération des produits
    public function getProducts(): array {
        return $this->products;
    }

    public function getProductsFromCategory(): array {
        try {
            $db = Database::getInstance();
            $query = $db->prepare('SELECT * FROM product WHERE category_id = :category_id');
            $query->execute(['category_id' => $this->id]);
            
            $this->products = [];
            while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
                // Vérifier si c'est un vêtement ou un produit électronique
                $clothingQuery = $db->prepare('SELECT * FROM clothing WHERE product_id = :product_id');
                $clothingQuery->execute(['product_id' => $data['id']]);
                $clothingData = $clothingQuery->fetch();

                if ($clothingData) {
                    $this->products[] = new Clothing(
                        $data['id'],
                        $data['name'],
                        json_decode($data['photos'], true) ?? [],
                        $data['price'],
                        $data['description'],
                        $data['quantity'],
                        $data['category_id'],
                        $clothingData['size'],
                        $clothingData['color'],
                        $clothingData['type'],
                        $clothingData['material_fee']
                    );
                    continue;
                }

                $electronicQuery = $db->prepare('SELECT * FROM electronic WHERE product_id = :product_id');
                $electronicQuery->execute(['product_id' => $data['id']]);
                $electronicData = $electronicQuery->fetch();

                if ($electronicData) {
                    $this->products[] = new Electronic(
                        $data['id'],
                        $data['name'],
                        json_decode($data['photos'], true) ?? [],
                        $data['price'],
                        $data['description'],
                        $data['quantity'],
                        $data['category_id'],
                        $electronicData['brand'],
                        $electronicData['warranty_fee']
                    );
                }
            }

            return $this->products;

        } catch (PDOException $e) {
            return [];
        }
    }

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