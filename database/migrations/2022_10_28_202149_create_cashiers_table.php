<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vault_id')->nullable()->constrained('vaults');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('title')->nullable();
            $table->text('observations')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->datetime('closed_at')->nullable();
            $table->foreignId('closeUser_id')->nullable()->constrained('users');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashiers');
    }
}
