<?php

namespace App\Interface;

interface EntityInterface {
    public function create(): bool;
    public function update(): bool;
} 