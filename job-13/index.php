<?php

require_once 'classes/Database.php';
require_once 'classes/Product.php';
require_once 'classes/Clothing.php';
require_once 'classes/Electronic.php';

// Test de findOneById
$clothing = Clothing::findOneById(1);
if ($clothing) {
    echo "Vêtement trouvé :\n";
    echo "Nom : " . $clothing->getName() . "\n";
    echo "Taille : " . $clothing->getSize() . "\n";
    echo "Couleur : " . $clothing->getColor() . "\n";
}

// Test de findAll
echo "\nListe de tous les vêtements :\n";
$allClothings = Clothing::findAll();
foreach ($allClothings as $item) {
    echo "- {$item->getName()} ({$item->getSize()}, {$item->getColor()})\n";
}

// Test avec un nouveau vêtement
$newClothing = new Clothing(
    null,
    'Nouveau T-shirt',
    ['tshirt.jpg'],
    2999,
    'Description du T-shirt',
    10,
    1,
    'M',
    'Rouge',
    'T-shirt',
    500
);

if ($newClothing->create()) {
    echo "\nNouveau vêtement créé avec l'ID : " . $newClothing->getId() . "\n";
    
    // Test de update
    $newClothing->setColor('Bleu');
    if ($newClothing->update()) {
        echo "Vêtement mis à jour avec succès\n";
    }
}

// Test avec un produit électronique
$electronic = Electronic::findOneById(1);
if ($electronic) {
    echo "\nProduit électronique trouvé :\n";
    echo "Nom : " . $electronic->getName() . "\n";
    echo "Marque : " . $electronic->getBrand() . "\n";
    echo "Prix : " . $electronic->getPrice() . "\n";
}

// Test de findAll pour les produits électroniques
echo "\nListe de tous les produits électroniques :\n";
$allElectronics = Electronic::findAll();
foreach ($allElectronics as $item) {
    echo "- {$item->getName()} ({$item->getBrand()})\n";
} 