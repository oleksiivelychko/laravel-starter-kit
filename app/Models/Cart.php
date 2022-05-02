<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $hash
 */
class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = ['hash'];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class)->with('product');
    }
}
