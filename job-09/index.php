<?php

require_once 'classes/Database.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';
require_once 'classes/Clothing.php';
require_once 'classes/Electronic.php';

// Test avec une catégorie
$category = new Category(null, "Vêtements", "Tous types de vêtements");
if ($category->create()) {
    echo "Catégorie créée avec l'ID : " . $category->getId() . "\n";
}

// Test avec un vêtement
$clothing = new Clothing(
    null,
    "T-shirt",
    ["tshirt.jpg"],
    1999,
    "Un super t-shirt",
    10,
    $category->getId(),
    "M",
    "Bleu",
    "T-shirt",
    500
);

if ($clothing->create()) {
    echo "Vêtement créé avec l'ID : " . $clothing->getId() . "\n";
    
    // Test des interfaces
    if ($clothing instanceof EntityInterface) {
        echo "C'est une entité !\n";
    }
    
    if ($clothing instanceof StockableInterface) {
        echo "C'est un produit stockable !\n";
        echo "En stock ? : " . ($clothing->isInStock() ? "Oui" : "Non") . "\n";
    }
}

// Test avec un produit électronique
$electronic = new Electronic(
    null,
    "Smartphone",
    ["smartphone.jpg"],
    49900,
    "Un super smartphone",
    5,
    $category->getId(),
    "TechBrand",
    2000
);

if ($electronic->create()) {
    echo "\nProduit électronique créé avec l'ID : " . $electronic->getId() . "\n";
    
    // Test des interfaces
    if ($electronic instanceof EntityInterface) {
        echo "C'est une entité !\n";
    }
    
    if ($electronic instanceof StockableInterface) {
        echo "C'est un produit stockable !\n";
        echo "En stock ? : " . ($electronic->isInStock() ? "Oui" : "Non") . "\n";
    }
} 