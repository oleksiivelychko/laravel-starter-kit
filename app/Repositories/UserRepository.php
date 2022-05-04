<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class UserRepository
{
    public function search(int $user_id): Model|Collection|array|User
    {
        $user = User::find($user_id);
        if (!$user) {
            throw new ModelNotFoundException('User not found by ID ' . $user_id);
        }
        return $user;
    }
}
