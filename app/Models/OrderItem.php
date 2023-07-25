<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property Order $order
 * @property int   $order_id
 * @property float $price
 * @property int   $quantity
 * @property int   $product_id
 */
class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'price',
        'quantity',
        'product_id',
    ];

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'price' => 'numeric|min:0.1',
            'quantity' => 'numeric|min:0.1',
        ];
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function calculateAndSave(array $validatedData): bool
    {
        $this->fill($validatedData);
        $saved = $this->save();
        if ($saved) {
            $this->order->total_price =
                array_reduce(
                    $this->order->items->toArray(),
                    fn ($total, $cartItem) => $cartItem['price'] * $cartItem['quantity']
                );
            $this->order->save();
        }

        return $saved;
    }
}
