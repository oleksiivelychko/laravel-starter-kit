<?php

namespace App\Interfaces;


interface Entity
{
    function saveTranslations(array $data): void;

    function saveModel(array $data): bool;
}
