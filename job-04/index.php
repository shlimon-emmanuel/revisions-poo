<?php

require_once 'classes/Product.php';

// Test avec un tableau de données complet
$productData = [
    'id' => 1,
    'name' => 'T-shirt',
    'photos' => json_encode(['tshirt1.jpg', 'tshirt2.jpg']),
    'price' => 1999,
    'description' => 'Un super t-shirt',
    'quantity' => 10,
    'category_id' => 1
];

// Test de la méthode fromArray
$product = Product::fromArray($productData);

// Affichage des résultats
echo "=== Test de fromArray et hydrate ===\n";
echo "ID: " . $product->getId() . "\n";
echo "Nom: " . $product->getName() . "\n";
echo "Prix: " . ($product->getPrice() / 100) . "€\n";
echo "Photos: " . implode(', ', $product->getPhotos()) . "\n";
echo "Quantité: " . $product->getQuantity() . "\n";

// Test avec des données partielles
$partialData = [
    'name' => 'Pantalon',
    'price' => 3999
];

$partialProduct = Product::fromArray($partialData);
echo "\n=== Test avec données partielles ===\n";
echo "Nom: " . $partialProduct->getName() . "\n";
echo "Prix: " . ($partialProduct->getPrice() / 100) . "€\n";
echo "Quantité (valeur par défaut): " . $partialProduct->getQuantity() . "\n";

// Test de la méthode toArray
$arrayResult = $product->toArray();
echo "\n=== Test de toArray ===\n";
echo "Données du tableau :\n";
print_r($arrayResult); 