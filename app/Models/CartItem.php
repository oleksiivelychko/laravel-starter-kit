<?php

namespace App\Models;

use App\Helpers\LocaleHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;


/**
 * @property int $quantity
 * @property int $cart_id
 * @property int $product_id
 */
class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $fillable = ['cart_id', 'quantity', 'product_id'];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public static function selectCartItems(string $hash): array
    {
        $items = DB::table('cart_items as ci')
            ->select(['ci.id','p.id as product_id','p.name','p.price','ci.quantity'])
            ->join('carts as c', 'c.id', '=', 'ci.cart_id')
            ->join('products as p', 'p.id', '=', 'ci.product_id')
            ->where('c.hash', $hash)
            ->get()
            ->toArray();

        foreach ($items as $item) {
            $item->name = LocaleHelper::translateObject($item->name);
        }

        return $items;
    }
}
