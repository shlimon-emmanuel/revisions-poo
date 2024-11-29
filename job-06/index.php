<?php

require_once 'classes/Database.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';

// Récupération d'une catégorie
$category = Category::findOneById(1);

if ($category) {
    echo "Catégorie : " . $category->getName() . "\n";
    echo "Nombre de produits : " . $category->countProducts() . "\n";
} 