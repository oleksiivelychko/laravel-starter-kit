<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

interface Pagination
{
    public function pagination(Request $request, ?string $locale = null): LengthAwarePaginator;

    public function getPaginationLimit(): int;
}
