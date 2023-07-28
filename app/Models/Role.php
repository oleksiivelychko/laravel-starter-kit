<?php

namespace App\Models;

use App\Contracts\Pagination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @method static whereIn(string $string, array $roles)
 */
class Role extends Model implements Pagination
{
    public const DEFAULT_ROLES = [
        'GUEST' => 'guest',
        'USER' => 'user',
        'ADMIN' => 'administrator',
    ];

    protected $table = 'roles';

    protected $fillable = ['name', 'slug'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    public function setPermissions(...$permissions): void
    {
        if (isset($permissions[0]) && is_array($permissions[0])) {
            $permissions = $permissions[0];
        }

        $existsPermissionsIds = $this->permissions()->pluck('permission_id')->toArray();
        $passedPermissionsIds = Permission::whereIn('slug', $permissions)->pluck('id')->toArray();

        $intersection = array_intersect($existsPermissionsIds, $passedPermissionsIds);
        $createPermissionsIds = array_diff($passedPermissionsIds, $intersection);
        $deletePermissionsIds = array_diff($existsPermissionsIds, $intersection);

        $this->permissions()->attach($createPermissionsIds);
        $this->permissions()->detach($deletePermissionsIds);
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
