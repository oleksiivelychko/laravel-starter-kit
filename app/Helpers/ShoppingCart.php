<?php

namespace App\Helpers;

use App\Models\Cart;
use App\Models\CartItem;
use Exception;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class ShoppingCart
{
    const COOKIE_NAME = 'shopping_cart';
    const COOKIE_TIME = 10080; // 1 week

    private static ?Cart $instance = null;

    public static function getCartInstance(): ?Cart
    {
        return self::$instance;
    }

    /**
     * Create or load instance of shopping cart by passed cookie hash value.
     */
    public static function load()
    {
        $cartHash = Cookie::get('shopping_cart');
        if ($cartHash) {
            /** @var Cart $cart */
            $cart = Cart::whereHash($cartHash)->first();
            if (!$cart) {
                $cart = new Cart();
                $cart->hash = $cartHash;
                $cart->save();
                Cookie::queue(self::COOKIE_NAME, $cartHash, self::COOKIE_TIME);
            }
        } else {
            $cart = new Cart();
            $cart->hash = sha1(microtime() . Str::random());
            $cart->save();
            Cookie::queue(self::COOKIE_NAME, $cart->hash, self::COOKIE_TIME);
        }

        self::$instance = $cart;
    }

    public static function addToCart(int $productId): array
    {
        if (!self::$instance) {
            self::load();
        }

        $cartItem = CartItem::whereCartId(self::$instance->id)->whereProductId($productId)->first();
        if (!$cartItem) {
            $cartItem = new CartItem();
            $cartItem->quantity = 1;
            $cartItem->product_id = $productId;
            $cartItem->cart_id = self::$instance->id;
            $saved = $cartItem->save();
        } else {
            $cartItem->quantity += 1;
            $saved = $cartItem->update();
        }

        if ($saved) {
            return self::getItems();
        }

        return [];
    }

    /**
     * @throws Exception
     */
    public static function removeFromCart(int $cartItemId): array
    {
        $deleted = CartItem::find($cartItemId)->delete();
        if ($deleted) {
            if (!self::$instance) {
                self::load();
            }
            return [
                'deleted'   => true,
                'items'     => self::getItems()
            ];
        }

        return [
            'deleted'   => false,
            'items'     => []
        ];
    }

    public static function getItems(): array
    {
        return CartItem::selectCartItems(self::$instance->hash);
    }
}
