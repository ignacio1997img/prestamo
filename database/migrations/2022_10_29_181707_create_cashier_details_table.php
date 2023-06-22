<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashierDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashier_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashier_id')->nullable()->constrained('cashiers');
            $table->decimal('cash_value', 10, 2)->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
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
        Schema::dropIfExists('cashier_details');
    }
}
