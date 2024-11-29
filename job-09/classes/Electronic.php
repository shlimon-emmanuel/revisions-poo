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

    // Getters
    public function getBrand(): string {
        return $this->brand;
    }

    public function getWarrantyFee(): int {
        return $this->warranty_fee;
    }

    // Méthodes CRUD
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
        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            // Mise à jour de la table product
            $query = $db->prepare('
                UPDATE product 
                SET name = :name,
                    photos = :photos,
                    price = :price,
                    description = :description,
                    quantity = :quantity,
                    category_id = :category_id
                WHERE id = :id
            ');

            $result = $query->execute([
                'id' => $this->id,
                'name' => $this->name,
                'photos' => json_encode($this->photos),
                'price' => $this->price,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'category_id' => $this->category_id
            ]);

            if ($result) {
                // Mise à jour de la table electronic
                $query = $db->prepare('
                    UPDATE electronic 
                    SET brand = :brand,
                        warranty_fee = :warranty_fee
                    WHERE product_id = :product_id
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
}
 