<?php

namespace App\Interfaces;

interface Entity
{
    public function saveTranslations(array $data): void;

    public function saveModel(array $data): bool;
}
