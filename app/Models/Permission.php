<?php

namespace App\Models;

use App\Contracts\Pagination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;


class Permission extends Model implements Pagination
{
    protected $table = 'permissions';

    protected $fillable = ['name', 'slug'];

    public const ADMIN_PERMISSIONS = [
        'manage-acl',
        'manage-goods',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'roles_permissions');
    }

    public function pagination(Request $request, ?string $locale=null): LengthAwarePaginator
    {
        $sortColumn = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');

        return $this->select(['id', 'name', 'slug', 'updated_at'])
            ->orderBy($sortColumn, $sortDirection)
            ->paginate($this->getPaginationLimit());
    }

    public function getPaginationLimit(): int
    {
        return config('settings.schema.pagination_limit', 10);
    }
}
