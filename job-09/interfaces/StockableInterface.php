<?php

interface StockableInterface {
    public function addStock(int $quantity): void;
    public function removeStock(int $quantity): void;
    public function isInStock(): bool;
} 