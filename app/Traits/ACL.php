<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * Access Control List
 *
 * @method belongsToMany(string $class, string $string)
 * @property-read $permissions_ids
 * @property-read $roles_ids
 * @property-read $roles
 * @property-read $permissions
 */
trait ACL
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }

    public function hasRole(...$roles): bool
    {
        return $this->_hasRoles($roles);
    }

    public function hasRoles(array $roles): bool
    {
        return $this->_hasRoles($roles);
    }

    private function _hasRoles($roles): bool
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }

        return false;
    }

    public function hasPermissionThroughRole(Permission $permission): bool
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }

        return false;
    }

    public function hasPermission(Permission $permission): bool
    {
        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }

    public function hasPermissionTo(Permission $permission): bool
    {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    public function assignRoles(...$roles): void
    {
        if (isset($roles[0]) && is_array($roles[0])) {
            $roles = $roles[0];
        }

        $existsRolesIds = $this->roles_ids;
        $passedRolesIds = Role::whereIn('slug', $roles)->pluck('id')->toArray();
        $intersection = array_intersect($existsRolesIds, $passedRolesIds);
        $createRolesIds = array_diff($passedRolesIds, $intersection);
        $deleteRolesIds = array_diff($existsRolesIds, $intersection);
        $this->roles()->attach($createRolesIds);
        $this->roles()->detach($deleteRolesIds);
    }

    public function assignPermissions(...$permissions): void
    {
        if (isset($permissions[0]) && is_array($permissions[0])) {
            $permissions = $permissions[0];
        }

        $existsPermissionsIds = $this->permissions_ids;
        $passedPermissionsIds = Permission::whereIn('slug', $permissions)->pluck('id')->toArray();
        $intersection = array_intersect($existsPermissionsIds, $passedPermissionsIds);
        $createPermissionsIds = array_diff($passedPermissionsIds, $intersection);
        $deletePermissionsIds = array_diff($existsPermissionsIds, $intersection);
        $this->permissions()->attach($createPermissionsIds);
        $this->permissions()->detach($deletePermissionsIds);
    }

    public function getRolesIdsAttribute(): array
    {
        return $this->roles()->pluck('role_id')->toArray();
    }

    public function getPermissionsIdsAttribute(): array
    {
        return $this->permissions()->pluck('permission_id')->toArray();
    }
}
