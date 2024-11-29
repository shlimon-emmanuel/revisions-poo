<?php

require_once 'interfaces/StockableInterface.php';
require_once 'classes/Database.php';
require_once 'classes/AbstractProduct.php';
require_once 'classes/Clothing.php';
require_once 'classes/Electronic.php';

try {
    echo "=== Test de gestion des stocks ===\n";
    
    // Test avec un vêtement
    $clothing = Clothing::findOneById(1);
    if ($clothing) {
        echo "\n--- Test avec un vêtement ---\n";
        echo "Stock initial du vêtement '{$clothing->getName()}' : {$clothing->getQuantity()}\n";
        
        // Ajouter du stock
        if ($clothing->addStocks(5)) {
            echo "✅ Ajout de 5 articles réussi. Nouveau stock : {$clothing->getQuantity()}\n";
        } else {
            echo "❌ Échec de l'ajout de stock\n";
        }
        
        // Retirer du stock
        try {
            if ($clothing->removeStocks(2)) {
                echo "✅ Retrait de 2 articles réussi. Nouveau stock : {$clothing->getQuantity()}\n";
            } else {
                echo "❌ Échec du retrait de stock\n";
            }
        } catch (Exception $e) {
            echo "❌ Erreur lors du retrait : " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ Aucun vêtement trouvé avec l'ID 1\n";
    }

    // Test avec un produit électronique
    $electronic = Electronic::findOneById(1);
    if ($electronic) {
        echo "\n--- Test avec un produit électronique ---\n";
        echo "Stock initial du produit '{$electronic->getName()}' : {$electronic->getQuantity()}\n";
        
        // Ajouter du stock
        if ($electronic->addStocks(3)) {
            echo "✅ Ajout de 3 articles réussi. Nouveau stock : {$electronic->getQuantity()}\n";
        } else {
            echo "❌ Échec de l'ajout de stock\n";
        }
        
        // Retirer du stock
        try {
            if ($electronic->removeStocks(1)) {
                echo "✅ Retrait d'1 article réussi. Nouveau stock : {$electronic->getQuantity()}\n";
            } else {
                echo "❌ Échec du retrait de stock\n";
            }
        } catch (Exception $e) {
            echo "❌ Erreur lors du retrait : " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ Aucun produit électronique trouvé avec l'ID 1\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur générale : " . $e->getMessage() . "\n";
}

echo "\n=== Fin des tests ===\n"; 