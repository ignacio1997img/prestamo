<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //  :::::::::::::::::::::::::::::::::::::::::::::  NO ELIMINAR LA MIGRACION ::::::::::::::::::::::::::::
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->smallInteger('status')->default(1);
            $table->foreignId('registerUser_id')->nullable()->constrained('users');
            $table->string('ci')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
