<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('order_id')->nullable();
            $table->uuid('product_id');
            $table->uuid('buyer_id');
            $table->uuid('seller_id');
            $table->enum('type', config('general.feedback_type'))->default('neutral');
            $table->decimal('rating')->default(0);
            $table->text('message')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('SET NULL');
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
        Schema::dropIfExists('feedback');
    }
}
