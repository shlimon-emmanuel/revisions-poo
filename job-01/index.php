<?php

require_once 'classes/Product.php';

// Création d'une instance de Product
$product = new Product(
    1,
    "Téléphone",
    ["phone1.jpg", "phone2.jpg"],
    999,
    "Un super téléphone",
    10
);

// Test des getters
echo "ID: " . $product->getId() . "\n";
echo "Nom: " . $product->getName() . "\n";
echo "Prix: " . $product->getPrice() . "€\n";

// Test des setters
$product->setPrice(899);
$product->setQuantity(15);

echo "Nouveau prix: " . $product->getPrice() . "€\n";
echo "Nouvelle quantité: " . $product->getQuantity() . "\n"; 