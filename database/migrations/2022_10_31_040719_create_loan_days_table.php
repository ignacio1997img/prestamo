<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_days', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('loan_id')->nullable()->constrained('loans');

            $table->integer('number')->nullable();
            $table->date('date')->nullable();

            $table->decimal('debt',11, 2)->nullable();
            $table->decimal('amount',11, 2)->nullable();

            $table->smallInteger('late')->default(0);
            $table->decimal('lateN',8,2)->nullable();

            $table->smallInteger('status')->default(1);
            $table->timestamps();
            $table->foreignId('register_userId')->nullable()->constrained('users');
            $table->string('register_agentType')->nullable();

            $table->softDeletes();
            $table->foreignId('deleted_userId')->nullable()->constrained('users');
            $table->string('deleted_agentType')->nullable();
            $table->string('deletedKey')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_days');
    }
}
