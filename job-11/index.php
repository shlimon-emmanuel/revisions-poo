<?php

require_once 'classes/Database.php';
require_once 'classes/Product.php';
require_once 'classes/Clothing.php';
require_once 'classes/Electronic.php';

// Création d'un vêtement
$clothing = new Clothing(
    null,
    'T-shirt Premium',
    ['tshirt1.jpg'],
    2999,
    'T-shirt en coton bio',
    50,
    1, // category_id
    'L',
    'Blue',
    'T-shirt',
    500 // material_fee
);

// Création d'un produit électronique
$electronic = new Electronic(
    null,
    'Smartphone XYZ',
    ['phone1.jpg'],
    99900,
    'Dernier modèle',
    10,
    2, // category_id
    'TechBrand',
    5000 // warranty_fee
);

// Test de création
if ($clothing->create()) {
    echo "Vêtement créé avec succès !\n";
}

if ($electronic->create()) {
    echo "Produit électronique créé avec succès !\n";
} 