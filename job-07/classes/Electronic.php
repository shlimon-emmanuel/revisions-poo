<?php

require_once 'Product.php';

class Electronic extends Product {
    private string $brand;
    private int $warranty_fee;

    public function __construct(
        ?int $id = null,
        string $name = '',
        array $photos = [],
        int $price = 0,
        string $description = '',
        int $quantity = 0,
        ?int $category_id = null,
        string $brand = '',
        int $warranty_fee = 0
    ) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $category_id);
        $this->brand = $brand;
        $this->warranty_fee = $warranty_fee;
    }

    // Getters et Setters spécifiques
    public function getBrand(): string {
        return $this->brand;
    }

    public function getWarrantyFee(): int {
        return $this->warranty_fee;
    }

    // Implémentation des méthodes abstraites
    public function create(): bool {
        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            // Insertion dans la table product
            $query = $db->prepare('
                INSERT INTO product (name, photos, price, description, quantity, category_id)
                VALUES (:name, :photos, :price, :description, :quantity, :category_id)
            ');

            $result = $query->execute([
                'name' => $this->name,
                'photos' => json_encode($this->photos),
                'price' => $this->price,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'category_id' => $this->category_id
            ]);

            if ($result) {
                $this->id = (int) $db->lastInsertId();

                // Insertion dans la table electronic
                $query = $db->prepare('
                    INSERT INTO electronic (product_id, brand, warranty_fee)
                    VALUES (:product_id, :brand, :warranty_fee)
                ');

                $result = $query->execute([
                    'product_id' => $this->id,
                    'brand' => $this->brand,
                    'warranty_fee' => $this->warranty_fee
                ]);

                if ($result) {
                    $db->commit();
                    return true;
                }
            }

            $db->rollBack();
            return false;

        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }

    public function update(): bool {
        // Implémentation similaire à create() mais avec UPDATE
        // ... code d'update ...
        return true;
    }
} 