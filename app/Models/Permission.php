<?php

namespace App\Models;

use App\Interfaces\Pagination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @property mixed $roles
 *
 * @method static whereIn(string $string, array $permissions)
 * @method select(string[] $array)
 */
class Permission extends Model implements Pagination
{
    public const ADMIN_PERMISSIONS = [
        'manage-acl',
        'manage-goods',
    ];
    protected $table = 'permissions';

    protected $fillable = ['name', 'slug'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'roles_permissions');
    }

    public function pagination(Request $request, ?string $locale = null): LengthAwarePaginator
    {
        $sortColumn = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');

        return $this->select(['id', 'name', 'slug', 'updated_at'])
            ->orderBy($sortColumn, $sortDirection)
            ->paginate($this->getPaginationLimit())
        ;
    }

    public function getPaginationLimit(): int
    {
        return config('settings.schema.pagination_limit', 10);
    }
}
