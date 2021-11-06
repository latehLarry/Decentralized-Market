<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id');
            $table->longtext('avatar')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('pin');
            $table->text('pgp_key')->nullable();
            $table->string('reference_code');
            $table->uuid('referenced_by')->nullable();
            $table->string('backup_monero_wallet')->nullable();
            $table->string('monero_wallet')->nullable();
            $table->string('become_monero_wallet')->nullable(); #Generates a wallet for the user to pay to become a seller
            $table->enum('currency', config('currencies'))->default('USD');
            $table->timestamp('last_login')->nullable();
            
            #Roles
            $table->boolean('admin')->default(false);
            $table->boolean('moderator')->default(false);
            $table->boolean('seller')->default(false);

            #Seller
            $table->text('seller_description')->nullable();
            $table->text('seller_rules')->nullable();
            $table->timestamp('seller_since')->nullable();
            $table->boolean('fe')->default(false);

            $table->timestamps();

            $table->primary('id');
            $table->foreign('referenced_by')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
