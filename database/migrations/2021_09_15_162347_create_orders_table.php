<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('product_id');
            $table->uuid('buyer_id');
            $table->uuid('seller_id');
            $table->text('address');
            $table->string('delivery_method');
            $table->string('quantity');
            $table->string('status')->default('waiting');
            $table->string('escrow_monero_wallet');
            $table->decimal('total');
            $table->decimal('total_in_monero');
            $table->boolean('finished')->default(false);
            $table->boolean('deleted')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
