<?php

namespace App;

use PDO;
use PDOException;
use InvalidArgumentException;
use RuntimeException;
use App\Abstract\AbstractProduct;
use App\Interface\StockableInterface;
use App\Interface\EntityInterface;

class Electronic extends AbstractProduct implements StockableInterface {
    // La classe hérite déjà de l'implémentation de EntityInterface
    // via AbstractProduct
    // ... reste du code inchangé ...
} 