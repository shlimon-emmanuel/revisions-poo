<?php

require_once 'Database.php';
require_once 'Clothing.php';
require_once 'Electronic.php';

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

    // Getters et Setters comme avant...

    // Méthodes abstraites que les classes enfants devront implémenter
    abstract public function create(): bool;
    abstract public function update(): bool;

    /**
     * Trouve un produit par son ID et retourne l'instance appropriée (Clothing ou Electronic)
     */
    public static function findOneById(int $id): ?self {
        try {
            $db = Database::getInstance();
            
            // D'abord, récupérer les données du produit
            $query = $db->prepare('SELECT * FROM product WHERE id = :id');
            $query->execute(['id' => $id]);
            $productData = $query->fetch();

            if (!$productData) {
                return null;
            }

            // Ensuite, vérifier si c'est un vêtement
            $query = $db->prepare('SELECT * FROM clothing WHERE product_id = :id');
            $query->execute(['id' => $id]);
            $clothingData = $query->fetch();

            if ($clothingData) {
                return new Clothing(
                    $productData['id'],
                    $productData['name'],
                    json_decode($productData['photos'], true) ?? [],
                    $productData['price'],
                    $productData['description'],
                    $productData['quantity'],
                    $productData['category_id'],
                    $clothingData['size'],
                    $clothingData['color'],
                    $clothingData['type'],
                    $clothingData['material_fee']
                );
            }

            // Sinon, vérifier si c'est un produit électronique
            $query = $db->prepare('SELECT * FROM electronic WHERE product_id = :id');
            $query->execute(['id' => $id]);
            $electronicData = $query->fetch();

            if ($electronicData) {
                return new Electronic(
                    $productData['id'],
                    $productData['name'],
                    json_decode($productData['photos'], true) ?? [],
                    $productData['price'],
                    $productData['description'],
                    $productData['quantity'],
                    $productData['category_id'],
                    $electronicData['brand'],
                    $electronicData['warranty_fee']
                );
            }

            return null;

        } catch (PDOException $e) {
            return null;
        }
    }
} 