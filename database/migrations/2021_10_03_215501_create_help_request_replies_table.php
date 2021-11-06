<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpRequestRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('help_request_replies', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('helprequest_id');
            $table->uuid('user_id');
            $table->text('message');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('helprequest_id')->references('id')->on('help_requests')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('help_request_replies');
    }
}
