<?php

namespace App\Collection;

use App\Interface\EntityInterface;

class EntityCollection {
    /**
     * @var EntityInterface[]
     */
    private array $entities = [];

    /**
     * @param EntityInterface[] $entities
     */
    public function __construct(array $entities = []) {
        foreach ($entities as $entity) {
            $this->add($entity);
        }
    }

    /**
     * Ajoute une entité à la collection
     */
    public function add(EntityInterface $entity): self {
        $id = $entity->getId();
        if ($id !== null) {
            $this->entities[$id] = $entity;
        } else {
            $this->entities[] = $entity;
        }
        return $this;
    }

    /**
     * Retire une entité de la collection
     */
    public function remove(EntityInterface $entity): self {
        $id = $entity->getId();
        if ($id !== null && isset($this->entities[$id])) {
            unset($this->entities[$id]);
        } else {
            $this->entities = array_filter(
                $this->entities,
                fn($e) => $e !== $entity
            );
        }
        return $this;
    }

    /**
     * Récupère les entités liées à une entité donnée
     */
    public function retrieve(EntityInterface $entity): self {
        // Cette méthode dépendra du type d'entité et de la relation
        // Par exemple, pour une catégorie, on récupère tous les produits associés
        if ($entity instanceof \App\Category) {
            try {
                $db = \App\Database::getInstance();
                $query = $db->prepare('
                    SELECT * FROM product 
                    WHERE category_id = :category_id
                ');
                
                $query->execute(['category_id' => $entity->getId()]);
                $products = $query->fetchAll(PDO::FETCH_ASSOC);

                $this->entities = [];
                foreach ($products as $productData) {
                    // Détermine le type de produit (Clothing ou Electronic)
                    $type = $this->determineProductType($productData['id']);
                    if ($type) {
                        $this->add($type::findOneById($productData['id']));
                    }
                }
            } catch (PDOException $e) {
                // Gestion des erreurs
            }
        }

        return $this;
    }

    /**
     * Détermine le type de produit (Clothing ou Electronic)
     */
    private function determineProductType(int $productId): ?string {
        try {
            $db = \App\Database::getInstance();
            
            // Vérifie si c'est un vêtement
            $query = $db->prepare('SELECT 1 FROM clothing WHERE product_id = :id');
            $query->execute(['id' => $productId]);
            if ($query->fetch()) {
                return \App\Clothing::class;
            }

            // Vérifie si c'est un produit électronique
            $query = $db->prepare('SELECT 1 FROM electronic WHERE product_id = :id');
            $query->execute(['id' => $productId]);
            if ($query->fetch()) {
                return \App\Electronic::class;
            }

            return null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Retourne toutes les entités de la collection
     * @return EntityInterface[]
     */
    public function all(): array {
        return array_values($this->entities);
    }

    /**
     * Retourne le nombre d'entités dans la collection
     */
    public function count(): int {
        return count($this->entities);
    }

    /**
     * Vérifie si une entité existe dans la collection
     */
    public function has(EntityInterface $entity): bool {
        $id = $entity->getId();
        if ($id !== null) {
            return isset($this->entities[$id]);
        }
        return in_array($entity, $this->entities, true);
    }
} 