<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

interface Pagination
{
    function pagination(Request $request, ?string $locale=null): LengthAwarePaginator;

    function getPaginationLimit(): int;
}
