<?php

require_once 'classes/Database.php';
require_once 'classes/Category.php';
require_once 'classes/Product.php';

// Création d'un produit
$productData = [
    'id' => 7,
    'name' => 'T-shirt',
    'photos' => json_encode(['tshirt1.jpg']),
    'price' => 1999,
    'description' => 'Un super t-shirt',
    'quantity' => 10,
    'category_id' => 1
];

$product = Product::fromArray($productData);

// Affichage des informations du produit
echo "Informations du produit :\n";
echo "ID: " . $product->getId() . "\n";
echo "Nom: " . $product->getName() . "\n";
echo "Prix: " . ($product->getPrice() / 100) . "€\n";

// Récupération et affichage de la catégorie
$category = $product->getCategory();
if ($category) {
    echo "Catégorie: " . $category->getName() . "\n";
    echo "Description de la catégorie: " . $category->getDescription() . "\n";
} else {
    echo "Aucune catégorie associée\n";
} 