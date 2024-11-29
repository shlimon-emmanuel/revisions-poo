<?php

namespace App;

use PDO;
use PDOException;
use InvalidArgumentException;
use RuntimeException;
use App\Abstract\AbstractProduct;
use App\Interface\StockableInterface;
use App\Interface\EntityInterface;

class Clothing extends AbstractProduct implements StockableInterface {
    // ... reste du code inchangé ...
} 