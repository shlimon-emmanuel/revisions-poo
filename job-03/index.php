<?php

$db = new PDO(
    'mysql:host=localhost;dbname=draft-shop;charset=utf8',
    'root',
    '',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

// Test de récupération des catégories
$query = $db->query('SELECT * FROM category');
$categories = $query->fetchAll();

echo "Liste des catégories :\n";
foreach ($categories as $category) {
    echo "- {$category['name']}: {$category['description']}\n";
}

// Test de récupération des produits avec leur catégorie
$query = $db->query('
    SELECT p.*, c.name as category_name 
    FROM product p 
    LEFT JOIN category c ON p.category_id = c.id
');
$products = $query->fetchAll();

echo "\nListe des produits :\n";
foreach ($products as $product) {
    echo "- {$product['name']} (Catégorie: {$product['category_name']}) - Prix: " . 
         number_format($product['price']/100, 2) . "€\n";
} 