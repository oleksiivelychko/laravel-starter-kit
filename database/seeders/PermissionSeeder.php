<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Support\Str;

class PermissionSeeder extends SchemaSeeder
{
    public function __construct()
    {
        parent::__construct(Permission::class);
    }

    public function run(): void
    {
        $permissions = ['Manage goods', 'Manage ACL'];
        foreach ($permissions as $permission) {
            $model = new Permission();
            $model->name = $permission;
            $model->slug = Str::slug(lcfirst($permission));
            $model->save();
        }
    }
}
