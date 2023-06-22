<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->nullable()->constrained('loans');

            $table->decimal('latitude',9,6)->nullable();
            $table->decimal('longitude', 9,6)->nullable();

            $table->string('ci', 500)->nullable();
            $table->string('luz', 500)->nullable();
            $table->string('croquis', 500)->nullable();


            $table->string('business')->nullable();

            $table->foreignId('success_userId')->nullable()->constrained('users');
            $table->string('success_agentType')->nullable();
            $table->smallInteger('status')->default(2);

            $table->timestamps();
            $table->foreignId('register_userId')->nullable()->constrained('users');
            $table->string('register_agentType')->nullable();

            $table->softDeletes();
            $table->foreignId('deleted_userId')->nullable()->constrained('users');
            $table->string('deleted_agentType')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_requirements');
    }
}
