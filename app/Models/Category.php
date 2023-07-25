<?php

namespace App\Models;

use App\Interfaces\Entity;
use App\Interfaces\Pagination;
use App\Traits\Asset;
use App\Traits\Translation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * @property int      $id
 * @property null|int $parent_id
 * @property string   $slug
 * @property string   $name
 */
class Category extends Model implements Entity, Pagination
{
    use HasFactory;
    use Translation;
    use Asset;

    protected $table = 'categories';

    protected $fillable = [
        'name', 'slug', 'parent_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'categories_products');
    }

    public function pagination(Request $request, string $locale = null): LengthAwarePaginator
    {
        $sortColumn = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');

        return $this->select([
            'categories.id',
            "categories.name->{$locale} as name",
            'categories.slug',
            'categories.parent_id',
            'categories.created_at',
            "parents.name->{$locale} as parent",
        ])
            ->leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            ->orderBy($sortColumn, $sortDirection)
            ->paginate($this->getPaginationLimit())
        ;
    }

    public function getPaginationLimit(): int
    {
        return config('settings.schema.pagination_limit', 10);
    }

    /**
     * @throws \Throwable
     */
    public function saveModel(array $data): bool
    {
        $saved = false;

        try {
            DB::beginTransaction();

            $this->parent_id = $data['parent_id'] ?? null;
            $this->saveTranslations($data);
            $saved = $this->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('daily')->error(__FILE__.':'.__LINE__.' - '.$e->getMessage());
        }

        return $saved;
    }

    public function saveTranslations($data): void
    {
        $name = [];
        $slug = '';

        foreach (config('settings.languages') as $language => $locale) {
            $name[$locale] = $data['name__'.$locale] ?: '';
            if (config('app.fallback_locale') === $locale) {
                $slug = Str::slug($data['name__'.$locale] ?: '');
            }
        }

        $this->slug = $data['slug'] ?? $slug;
        $this->name = json_encode($name, JSON_UNESCAPED_UNICODE);
    }

    public function selectUniqueProductIds(): array
    {
        $ids = DB::select("
            select p.id
                from products p
                         join categories_products cp on p.id = cp.product_id
                where cp.category_id = {$this->id}
                group by p.id
                having (select count(_cp.product_id)
                        from categories_products _cp where p.id=_cp.product_id
                       ) = 1
        ");

        return array_map(function ($id) {
            return $id->id;
        }, $ids);
    }

    public function deleteUnusedProducts(array $ids): void
    {
        $productsImagesDir = (new Product())->getImagesFolder();

        array_map(function ($productId) use ($productsImagesDir) {
            $imagesDir = public_path('uploads')."/{$productsImagesDir}/{$productId}";
            if (File::exists($imagesDir)) {
                File::deleteDirectory($imagesDir);
            }
        }, $ids);

        Product::whereIn('id', $ids)->delete();
    }
}
