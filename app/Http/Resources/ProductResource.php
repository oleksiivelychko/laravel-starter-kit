<?php

namespace App\Http\Resources;

use App\Helpers\ImageHelper;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        /** @var Product $this */
        $generalImage = ImageHelper::showImage(
            $this->images_array[0] ?? null,
            $this->getImagesFolder().'/'.$this->id,
            '200x150'
        );

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->translate('name', $locale),
            'description' => $this->translate('description', $locale),
            'price' => $this->price,
            'image' => asset($generalImage['path']),
            'created_at' => $this->created_at->format('d.m.Y H:i:s'),
        ];
    }
}
