<?php

require_once 'Product.php';

class Clothing extends Product {
    private string $size;
    private string $color;
    private string $type;
    private int $material_fee;

    public function __construct(
        ?int $id = null,
        string $name = '',
        array $photos = [],
        int $price = 0,
        string $description = '',
        int $quantity = 0,
        ?int $category_id = null,
        string $size = '',
        string $color = '',
        string $type = '',
        int $material_fee = 0
    ) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $category_id);
        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
    }

    // Getters
    public function getSize(): string {
        return $this->size;
    }

    public function getColor(): string {
        return $this->color;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getMaterialFee(): int {
        return $this->material_fee;
    }

    // Implémentation des méthodes abstraites
    public function create(): self|false {
        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            // Insertion dans la table product
            $query = $db->prepare('
                INSERT INTO product (name, photos, price, description, quantity, category_id, created_at, updated_at)
                VALUES (:name, :photos, :price, :description, :quantity, :category_id, :created_at, :updated_at)
            ');

            $result = $query->execute([
                'name' => $this->name,
                'photos' => json_encode($this->photos),
                'price' => $this->price,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'category_id' => $this->category_id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ]);

            if ($result) {
                $this->id = (int) $db->lastInsertId();

                // Insertion dans la table clothing
                $query = $db->prepare('
                    INSERT INTO clothing (product_id, size, color, type, material_fee)
                    VALUES (:product_id, :size, :color, :type, :material_fee)
                ');

                $result = $query->execute([
                    'product_id' => $this->id,
                    'size' => $this->size,
                    'color' => $this->color,
                    'type' => $this->type,
                    'material_fee' => $this->material_fee
                ]);

                if ($result) {
                    $db->commit();
                    return $this;
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
                    category_id = :category_id,
                    updated_at = :updated_at
                WHERE id = :id
            ');

            $result = $query->execute([
                'id' => $this->id,
                'name' => $this->name,
                'photos' => json_encode($this->photos),
                'price' => $this->price,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'category_id' => $this->category_id,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($result) {
                // Mise à jour de la table clothing
                $query = $db->prepare('
                    UPDATE clothing 
                    SET size = :size,
                        color = :color,
                        type = :type,
                        material_fee = :material_fee
                    WHERE product_id = :product_id
                ');

                $result = $query->execute([
                    'product_id' => $this->id,
                    'size' => $this->size,
                    'color' => $this->color,
                    'type' => $this->type,
                    'material_fee' => $this->material_fee
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