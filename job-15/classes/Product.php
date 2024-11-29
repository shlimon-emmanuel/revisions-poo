<?php

class Product {
    // ... propriétés et méthodes existantes ...

    public function create(): bool {
        try {
            $db = Database::getInstance();
            $db->beginTransaction();

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
                $db->commit();
                return true;
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
        if (!$this->id) {
            return false;
        }

        try {
            $db = Database::getInstance();
            $db->beginTransaction();

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
                $db->commit();
                return true;
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