<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->role();
    }

    private function role()
    {
        /**
         * Example of usage:
         * @role('manager')
         * ...
         * @endrole
         */
        Blade::directive('role', function ($role) {
            return "<?php if (auth()->check() && auth()->user()->hasRole($role)) : ?>";
        });
        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });
    }
}
