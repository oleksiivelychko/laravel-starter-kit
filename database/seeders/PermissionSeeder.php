<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\Permission;


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
            $model = new Permission;
            $model->name = $permission;
            $model->slug = Str::slug(lcfirst($permission));
            $model->save();
        }
    }
}
