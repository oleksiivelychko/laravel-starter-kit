<?php

namespace App\Models;

use App\Helpers\ShoppingCart;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/**
 * @property-read int $id
 * @property float $total_price
 * @property int|null $user_id
 * @property bool $shipping_address_as_billing_address
 * @property mixed $items
 */
class Order extends Model
{
    protected $table = 'orders';

    public const STATUSES = [
        'NEW_ORDER'     => '0',
        'IN_PROGRESS'   => '1',
        'PAID'          => '2',
        'DELIVERY'      => '3',
        'COMPLETED'     => '4',
        'CANCELLED'     => '5',
    ];

    protected $fillable = [
        'user_id',
        'status',
        'customer_name',
        'customer_email',
        'shipping_address',
        'billing_address',
        'promo_code',
        'total_price',
        'discount',
        'shipping_address_as_billing_address',
    ];

    public function calculateAndSave(array $validatedData, array $cartItems=[]): bool
    {
        $this->fill($validatedData);

        if ($cartItems) {
            $this->total_price =
                array_reduce($cartItems, fn ($total, $cartItem) => $cartItem->price * $cartItem->quantity);
        } else {
            $this->total_price = 0.0;
        }

        $this->user_id = $validatedData['user_id'] ?? Auth::id();
        $this->shipping_address_as_billing_address = isset($validatedData['shipping_address_as_billing_address']);

        $saved = $this->save();
        if ($saved) {
            foreach ($cartItems as $cartItem) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $this->id;
                $orderItem->price = $cartItem->price;
                $orderItem->quantity = $cartItem->quantity;
                $orderItem->product_id = $cartItem->product_id;
                $orderItem->save();
            }

            $cartInstance = ShoppingCart::getCartInstance();
            $cartInstance?->delete();
        }

        return $saved;
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)->with('product');
    }

    public function pagination(Request $request, ?string $locale=null): LengthAwarePaginator
    {
        $sortColumn = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');

        return $this->select(['id', 'total_price', 'status', 'created_at'])
            ->orderBy($sortColumn, $sortDirection)
            ->paginate($this->getPaginationLimit());
    }

    public function getPaginationLimit(): int
    {
        return config('settings.schema.pagination_limit', 10);
    }
}
