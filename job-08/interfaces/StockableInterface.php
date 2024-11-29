<?php

interface StockableInterface {
    /**
     * Ajoute une quantité au stock
     */
    public function addStock(int $quantity): void;

    /**
     * Retire une quantité du stock
     */
    public function removeStock(int $quantity): void;

    /**
     * Vérifie si le produit est en stock
     */
    public function isInStock(): bool;
} 