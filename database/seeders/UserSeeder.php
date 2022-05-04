<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use App\Models\Role;


class UserSeeder extends SchemaSeeder
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function run(): void
    {
        $user = new User;
        $user->name = 'Admin';
        $user->email = 'admin@test.test';
        $user->password = 'secret';
        $user->email_verified_at = now();
        $user->save();
        $user->assignRoles(Role::DEFAULT_ROLES['ADMIN']);
        $user->assignPermissions(Permission::ADMIN_PERMISSIONS);

        $user = new User;
        $user->name = 'Test';
        $user->email = 'test@test.test';
        $user->password = 'secret';
        $user->email_verified_at = now();
        $user->save();
        $user->assignRoles(Role::DEFAULT_ROLES['USER']);
    }
}
