<?php

namespace Database\Seeders;

use App\Models\Role;


class RoleSeeder extends SchemaSeeder
{
    public function __construct()
    {
        parent::__construct(Role::class);
    }

    public function run(): void
    {
        $roles = [
            Role::DEFAULT_ROLES['GUEST'] => [],
            Role::DEFAULT_ROLES['USER'] => ['create-orders'],
            Role::DEFAULT_ROLES['ADMIN'] => ['manage-acl'],
        ];

        foreach ($roles as $role => $permissions) {
            $model = new Role;
            $model->name = ucfirst($role);
            $model->slug = $role;
            $model->save();
            if ($permissions) {
                $model->setPermissions($permissions);
            }
        }
    }
}
