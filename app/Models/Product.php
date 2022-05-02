<?php

namespace App\Models;

use App\Interfaces\UploadImages;
use App\Interfaces\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;
use Exception;
use App\Interfaces\Entity;
use App\Traits\Asset;
use App\Traits\Translation;
use Illuminate\Http\Request;

/**
 * @property string $images
 * @property float $price
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property array $categories_ids
 */
class Product extends Model implements Entity, UploadImages, Pagination
{
    use HasFactory, Translation, Asset;

    protected $table = 'products';

    protected $fillable = ['name', 'slug', 'price', 'description', 'images'];

    protected $appends = ['categories_ids'];

    protected string $imageField = 'images';

    protected string $imagesFolder = 'products_images';

    protected array $cropPresets = [[200,150],[900,400]];

    /**
     * The attributes that should be mutated to date.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getImageField(): string
    {
        return $this->imageField;
    }

    public function getImagesFolder(): string
    {
        return $this->imagesFolder;
    }

    public function getCropPresets(): array
    {
        return $this->cropPresets;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'categories_products');
    }

    public function getCategoriesIdsAttribute(): array
    {
        return array_map(fn($categoryId): int => intval($categoryId), $this->categories()->pluck('category_id')->toArray());
    }

    public function getImagesArrayAttribute(): array
    {
        return $this->images ? json_decode($this->images, true) : [];
    }

    public function pagination(Request $request, ?string $locale=null): LengthAwarePaginator
    {
        $sortColumn = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');

        return $this->select(['id', "name->$locale as name", 'slug', 'price', 'created_at'])
            ->orderBy($sortColumn, $sortDirection)
            ->paginate($this->getPaginationLimit());
    }

    public function getPaginationLimit(): int
    {
        return config('settings.schema.pagination_limit', 10);
    }

    /**
     * @throws Throwable
     */
    public function saveModel(array $data): bool
    {
        $saved = false;

        try {
            DB::beginTransaction();
            $this->price = $data['price'];
            $this->saveTranslations($data);
            $saved = $this->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('daily')->error(__FILE__.':'.__LINE__.' - '.$e->getMessage());
        }

        if ($saved) {
            $this->saveMany($data);
            if (isset($data[$this->getImageField()])) {
                $this->uploadImages($data);
            }
        }

        return $saved;
    }

    public function saveTranslations($data): void
    {
        $name = $description = [];
        $slug = '';
        foreach (config('settings.languages') as $language => $locale) {
            $name[$locale] = $data['name__' . $locale] ?: '';
            $description[$locale] = $data['description__' . $locale] ?? '';
            if (config('app.fallback_locale') === $locale) {
                $slug = Str::slug($data['name__' . $locale] ?: '');
            }
        }

        $this->slug = $data['slug'] ?? $slug;
        $this->name = json_encode($name, JSON_UNESCAPED_UNICODE);
        $this->description = json_encode($description, JSON_UNESCAPED_UNICODE);
    }

    public function saveMany($data): void
    {
        $existsIds = $this->categories_ids;
        $passedIds = $data['categories'];

        $intersection = array_intersect($existsIds, $passedIds);
        $createIds = array_diff($passedIds, $intersection);
        $deleteIds = array_diff($existsIds, $intersection);

        $this->categories()->attach($createIds);
        $this->categories()->detach($deleteIds);
    }

    public function uploadImages(array $data): void
    {
        $images = $this->{$this->getImageField()} ? json_decode($this->{$this->getImageField()}) : [];
        foreach ($data[$this->getImageField()] as $image) {
            $filename = $this->uploadImage($image, true);
            if ($filename) {
                $images[] = $filename;
            }
        }

        $this->{$this->getImageField()} = json_encode($images, JSON_UNESCAPED_UNICODE);
        $this->save();
    }
}
