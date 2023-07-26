<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Access
{
    /**
     * Use as [middlewareName:@roleName#permissionName1#permissionName2...]
     * e.g. access:@role-slug#permission-slug#permission-slug...
     */
    public function handle(Request $request, \Closure $next, ...$rolesAndPermissions): mixed
    {
        if (auth()->guest()) {
            abort(403, 'Unauthenticated action.');
        }

        $roles = [];
        foreach ($rolesAndPermissions as $rolesAndPermission) {
            $explode = explode('#', $rolesAndPermission);
            if (Str::startsWith($explode[0], '@')) {
                $roles[Str::replaceFirst('@', '', $explode[0])] = array_slice($explode, 1);
            }
        }

        if (!auth()?->user()?->hasRoles(array_keys($roles))) {
            abort(403, 'Unauthorized action.');
        }

        foreach ($roles as $role => $permissions) {
            foreach ($permissions as $permission) {
                if (!auth()->user()->can($permission)) {
                    abort(403, 'Such user as `'.$role.'` does not have `'.$permission.'` permission.');
                }
            }
        }

        return $next($request);
    }
}
