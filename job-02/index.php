<?php

require_once 'classes/Category.php';
require_once 'classes/Product.php';

// Création d'une catégorie
$category = new Category(
    1,
    "Électronique",
    "Tous les produits électroniques"
);

// Création d'un produit avec une catégorie
$product = new Product(
    1,
    "Téléphone",
    ["phone1.jpg", "phone2.jpg"],
    999,
    "Un super téléphone",
    10,
    $category->getId()
);

// Test des getters
echo "Catégorie ID: " . $category->getId() . "\n";
echo "Nom de la catégorie: " . $category->getName() . "\n";
echo "Produit catégorie ID: " . $product->getCategoryId() . "\n";

// Test des setters
$category->setName("High-Tech");
$product->setCategoryId(2);

echo "Nouveau nom de catégorie: " . $category->getName() . "\n";
echo "Nouvelle catégorie du produit: " . $product->getCategoryId() . "\n"; 