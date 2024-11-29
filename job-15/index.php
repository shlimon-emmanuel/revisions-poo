<?php

require_once 'vendor/autoload.php';

use App\Category;

try {
    // Création d'une nouvelle catégorie
    $category = new Category(null, "Vêtements", "Tous types de vêtements");
    
    if ($category->create()) {
        echo "Catégorie créée avec succès avec l'ID : " . $category->getId() . "\n";
    } else {
        echo "Échec de la création de la catégorie\n";
    }

    // Mise à jour de la catégorie
    $category->setName("Vêtements et accessoires");
    if ($category->update()) {
        echo "Catégorie mise à jour avec succès\n";
    } else {
        echo "Échec de la mise à jour de la catégorie\n";
    }

    // Recherche d'une catégorie par ID
    $foundCategory = Category::findOneById($category->getId());
    if ($foundCategory) {
        echo "Catégorie trouvée : " . $foundCategory->getName() . "\n";
    } else {
        echo "Aucune catégorie trouvée avec cet ID\n";
    }

    // Récupération de toutes les catégories
    $allCategories = Category::findAll();
    echo "Liste de toutes les catégories :\n";
    foreach ($allCategories as $cat) {
        echo "- " . $cat->getName() . " : " . $cat->getDescription() . "\n";
    }

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
} 