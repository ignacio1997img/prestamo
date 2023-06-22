<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashierMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashier_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashier_id')->nullable()->constrained('cashiers');
            // $table->foreignId('cashier_id_from')->nullable()->constrained('cashiers');
            // $table->foreignId('cashier_id_to')->nullable()->constrained('cashiers');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->decimal('balance', 10, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('cashier_movements');
    }
}
