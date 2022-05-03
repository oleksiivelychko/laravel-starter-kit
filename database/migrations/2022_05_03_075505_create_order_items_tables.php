<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('status', [array_values(Order::STATUSES)])->default(Order::STATUSES['NEW_ORDER']);
            $table->string('customer_name', 100);
            $table->string('customer_email');
            $table->string('billing_address');
            $table->string('shipping_address')->nullable();
            $table->string('promo_code', 50)->nullable();
            $table->unsignedDecimal('total_price')->default(0.0);
            $table->unsignedDecimal('discount')->default(0.0);
            $table->boolean('shipping_address_as_billing_address')->default(true);
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            ;
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedDecimal('price')->default(0.0);
            $table->unsignedFloat('quantity')->default(0);
            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onUpdate('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
