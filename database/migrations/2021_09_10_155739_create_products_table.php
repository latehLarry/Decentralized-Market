<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('seller_id');
            $table->uuid('category_id')->nullable();
            $table->string('name');
            $table->text('description');
            $table->text('refund_policy');
            $table->enum('ships_from', array_keys(config('countries')));
            $table->enum('ships_to', array_keys(config('countries')));
            $table->boolean('deleted')->default(false);
            $table->boolean('featured')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
