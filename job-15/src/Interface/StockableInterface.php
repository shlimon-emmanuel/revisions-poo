<?php

namespace App\Interface;

interface StockableInterface {
    public function addStocks(int $quantity): bool;
    public function removeStocks(int $quantity): bool;
    public function getQuantity(): int;
} 