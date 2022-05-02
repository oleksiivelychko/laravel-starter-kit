<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;


class ImageHelper
{
    /**
     * https://stackoverflow.com/questions/1855996/crop-image-in-php
     */
    public static function crop(string $source, string $output_dirname, array $presets): bool
    {
        // source e.g. storage_path('app/public/seed/products/unsplash.jpg')
        if (!file_exists($source)) {
            return false;
        }

        foreach ($presets as $preset) {
            $filename = public_path('uploads/')
                .$output_dirname.'/'.$preset[0].'x'.$preset[1].'_'.File::name($source).'.'.File::extension($source);
            $image = null;

            switch (File::extension($source)) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($source);
                    break;
                case 'png':
                    $image = imagecreatefrompng($source);
                    break;
                default:
                    return false;
            }

            $thumb_width = $preset[0];
            $thumb_height = $preset[1];

            $width = imagesx($image);
            $height = imagesy($image);

            $original_aspect = $width / $height;
            $thumb_aspect = $thumb_width / $thumb_height;

            if ($original_aspect >= $thumb_aspect) {
                // If image is wider than thumbnail (in aspect ratio sense)
                $new_height = $thumb_height;
                $new_width = $width / ($height / $thumb_height);
            } else {
                // If the thumbnail is wider than the image
                $new_width = $thumb_width;
                $new_height = $height / ($width / $thumb_width);
            }

            $thumb = imagecreatetruecolor($thumb_width, $thumb_height);

            // Resize and crop
            imagecopyresampled(
                $thumb,
                $image,
                0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                0,
                0,
                $new_width,
                $new_height,
                $width,
                $height
            );

            switch (File::extension($source)) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($thumb, $filename, 80);
                    break;
                case 'png':
                    imagepng($thumb, $filename, 8);
                    break;
                default:
                    return false;
            }
        }

        return true;
    }

    public static function showImage($image, $folder, $preset=''): array
    {
        if ($image) {
            $originalPath = $folder.'/'.($preset ? $preset . '_' : '').$image;
            if (File::exists(public_path('uploads/') . $originalPath)) {
                return [
                    'exists' => true,
                    'path' => 'uploads/' . $originalPath
                ];
            }
        }

        return [
            'exists' => false,
            'path' => config('settings.assets.not-found')
        ];
    }
}
