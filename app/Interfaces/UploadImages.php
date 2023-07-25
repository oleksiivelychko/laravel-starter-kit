<?php

namespace App\Interfaces;

use Illuminate\Http\UploadedFile;

interface UploadImages
{
    public function getImageField(): string;

    public function getImagesFolder(): string;

    public function getCropPresets(): array;

    public function uploadImage(?UploadedFile $uploadedFile, $multiple = false): bool|string;

    public function uploadImages(array $data): void;
}
