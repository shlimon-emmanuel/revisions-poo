<?php

require_once 'classes/Product.php';

// Les deux façons d'instancier fonctionnent maintenant
$product1 = new Product(1, 'T-shirt', ['https://picsum.photos/200/300'], 1000, 'A beautiful T-shirt', 10);
$product2 = new Product();

// Test avec le premier produit
echo "Produit 1:\n";
echo "Nom: " . $product1->getName() . "\n";
echo "Prix: " . $product1->getPrice() . "€\n";

// Test avec le deuxième produit (valeurs par défaut)
echo "\nProduit 2:\n";
echo "Nom: " . $product2->getName() . " (chaîne vide par défaut)\n";
echo "Prix: " . $product2->getPrice() . "€ (0 par défaut)\n"; 