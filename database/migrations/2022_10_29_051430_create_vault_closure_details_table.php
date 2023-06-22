<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaultClosureDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vault_closure_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vault_closure_id')->nullable()->constrained('vault_closures');
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
        Schema::dropIfExists('vault_closure_details');
    }
}
