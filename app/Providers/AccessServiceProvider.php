<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AccessServiceProvider extends ServiceProvider
{
    /**
     * Example of usage: $user->can('manage-acl').
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('permissions')) {
                Permission::get()->map(function ($permission) {
                    Gate::define($permission->slug, function ($user) use ($permission) {
                        return $user->hasPermissionTo($permission);
                    });
                });
            }
        } catch (\Exception $e) {
            report($e);
        }
    }
}
