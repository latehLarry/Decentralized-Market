<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_messages', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('issuer_id')->nullable();
            $table->uuid('receiver_id')->nullable();
            $table->uuid('conversation_id');
            $table->longtext('message');
            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->foreign('issuer_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversation_messages');
    }
}
