<?php

require_once 'AbstractProduct.php';
require_once __DIR__ . '/../interfaces/StockableInterface.php';

class Electronic extends AbstractProduct implements StockableInterface {
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

    public static function findOneById(int $id): ?static {
        try {
            $db = Database::getInstance();
            
            $query = $db->prepare('
                SELECT p.*, e.brand, e.warranty_fee
                FROM product p
                JOIN electronic e ON e.product_id = p.id
                WHERE p.id = :id
            ');
            
            $query->execute(['id' => $id]);
            $data = $query->fetch();
            
            if ($data) {
                return new static(
                    $data['id'],
                    $data['name'],
                    json_decode($data['photos'], true),
                    $data['price'],
                    $data['description'],
                    $data['quantity'],
                    $data['category_id'],
                    $data['brand'],
                    $data['warranty_fee']
                );
            }
            
            return null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function addStocks(int $quantity): bool {
        try {
            $db = Database::getInstance();
            
            $query = $db->prepare('
                UPDATE product 
                SET quantity = quantity + :quantity
                WHERE id = :id
            ');
            
            $result = $query->execute([
                'id' => $this->id,
                'quantity' => $quantity
            ]);

            if ($result) {
                $this->quantity += $quantity;
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function removeStocks(int $quantity): bool {
        if ($this->quantity < $quantity) {
            throw new Exception("Stock insuffisant");
        }

        try {
            $db = Database::getInstance();
            
            $query = $db->prepare('
                UPDATE product 
                SET quantity = quantity - :quantity
                WHERE id = :id
            ');
            
            $result = $query->execute([
                'id' => $this->id,
                'quantity' => $quantity
            ]);

            if ($result) {
                $this->quantity -= $quantity;
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
} 