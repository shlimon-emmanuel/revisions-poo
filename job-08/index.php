<?php

require_once 'classes/Database.php';
require_once 'classes/Product.php';
require_once 'classes/Clothing.php';
require_once 'classes/Electronic.php';

// Test avec un vêtement
$clothing = new Clothing(
    null,
    "T-shirt",
    ["tshirt.jpg"],
    1999,
    "Un super t-shirt",
    10,
    2,
    "M",
    "Bleu",
    "T-shirt",
    500
);

if ($clothing->create()) {
    echo "=== Test avec un vêtement ===\n";
    echo "Stock initial : " . $clothing->getQuantity() . "\n";
    
    // Test addStock
    if ($clothing->addStock(5)) {
        echo "✅ Ajout de 5 articles réussi\n";
        echo "Après ajout : " . $clothing->getQuantity() . "\n";
    }
    
    // Test removeStock
    if ($clothing->removeStock(3)) {
        echo "✅ Retrait de 3 articles réussi\n";
        echo "Après retrait : " . $clothing->getQuantity() . "\n";
    }
    
    // Test isInStock
    echo "En stock ? : " . ($clothing->isInStock() ? "Oui" : "Non") . "\n";
}

// Test avec un produit électronique
$electronic = new Electronic(
    null,
    "Smartphone",
    ["smartphone.jpg"],
    49900,
    "Un super smartphone",
    5,
    1,
    "TechBrand",
    2000
);

if ($electronic->create()) {
    echo "\n=== Test avec un produit électronique ===\n";
    echo "Stock initial : " . $electronic->getQuantity() . "\n";
    
    // Test addStock
    if ($electronic->addStock(3)) {
        echo "✅ Ajout de 3 articles réussi\n";
        echo "Après ajout : " . $electronic->getQuantity() . "\n";
    }
    
    // Test removeStock
    if ($electronic->removeStock(2)) {
        echo "✅ Retrait de 2 articles réussi\n";
        echo "Après retrait : " . $electronic->getQuantity() . "\n";
    }
    
    // Test isInStock
    echo "En stock ? : " . ($electronic->isInStock() ? "Oui" : "Non") . "\n";
} 