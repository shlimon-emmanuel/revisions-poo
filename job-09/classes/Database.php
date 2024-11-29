<?php

interface EntityInterface {
    /**
     * Récupère l'ID de l'entité
     */
    public function getId(): ?int;

    /**
     * Définit l'ID de l'entité
     */
    public function setId(?int $id): void;
} 