<?php

require_once 'classes/Database.php';
require_once 'classes/Product.php';

echo "Recherche du produit avec l'ID 1 :\n";
$product = Product::findOneById(1);

if ($product) {
    echo "Produit trouvé :\n";
    echo "- Nom : " . $product->getName() . "\n";
    echo "- Prix : " . $product->getPrice() . "€\n";
    
    if ($product instanceof Clothing) {
        echo "- Type : Vêtement\n";
        echo "- Taille : " . $product->getSize() . "\n";
        echo "- Couleur : " . $product->getColor() . "\n";
    } elseif ($product instanceof Electronic) {
        echo "- Type : Produit électronique\n";
        echo "- Marque : " . $product->getBrand() . "\n";
        echo "- Garantie : " . $product->getWarrantyFee() . "€\n";
    }
} else {
    echo "Aucun produit trouvé avec cet ID.\n";
} 