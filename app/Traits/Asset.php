<?php

namespace App\Traits;

use App\Contracts\UploadImages;
use App\Exceptions\InterfaceInstanceException;
use App\Helpers\ImageHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

/**
 * @method getImagesFolder()
 * @method getImageField()
 * @method getCropPresets()
 */
trait Asset
{
    /**
     * @throws InterfaceInstanceException
     */
    public function showImage(string $preset = ''): string
    {
        $this->throwAway();

        if ($this->{$this->getImageField()}) {
            $originalPath = $this->getImagesFolder().'/'.$this->id.'/'.($preset ? $preset.'_' : '').$this->{$this->getImageField()};
            if (File::exists(public_path('uploads/').$originalPath)) {
                return 'uploads/'.$originalPath;
            }
        }

        return $this->notFoundImage();
    }

    /**
     * @throws InterfaceInstanceException
     */
    public function showFirstImage(string $preset = ''): string
    {
        $this->throwAway();

        $image = $this->{$this->getImageField()} ? call_user_func($this->getImageField().'_array')[0] : false;
        if ($image) {
            $originalPath = $this->getImagesFolder().'/'.$this->id.'/'.($preset ? $preset.'_' : '').$image;
            if (File::exists(public_path('uploads/').$originalPath)) {
                return 'uploads/'.$originalPath;
            }
        }

        return $this->notFoundImage();
    }

    /**
     *
     * @throws InterfaceInstanceException
     */
    public function uploadImage(?UploadedFile $uploadedFile, bool $multiple = false): bool|string
    {
        $this->throwAway();

        $outputDirname = $this->getImagesFolder().'/'.$this->id;
        $imagesDir = public_path('uploads').'/'.$outputDirname;
        $filename = md5($uploadedFile->getClientOriginalName()).'.'.$uploadedFile->extension();

        if (!$multiple) {
            if ($this->{$this->getImageField()} && File::exists($imagesDir)) {
                File::deleteDirectory($imagesDir);
            }
        }

        if (!File::exists($imagesDir)) {
            File::makeDirectory($imagesDir, 493, true);
        }

        $path = $uploadedFile->storeAs($outputDirname, $filename, 'uploads');
        if ($path) {
            ImageHelper::crop(
                public_path('uploads')."/{$outputDirname}/{$filename}",
                $outputDirname,
                $this->getCropPresets()
            );

            return $filename;
        }

        return false;
    }

    private function notFoundImage(): string
    {
        return config('settings.assets.not-found');
    }

    /**
     * @throws InterfaceInstanceException
     */
    private function throwAway(): void
    {
        if (!$this instanceof UploadImages) {
            throw new InterfaceInstanceException(
                sprintf(
                    'Class %s is not instance of %s.',
                    get_class($this),
                    UploadImages::class,
                )
            );
        }
    }
}
