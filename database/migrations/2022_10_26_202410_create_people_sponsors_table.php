<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleSponsorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people_sponsors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('people_id')->nullable()->constrained('people');
            $table->foreignId('sponsor_id')->nullable()->constrained('people');
            $table->text('observation')->nullable();
            
            $table->smallInteger('status')->default(1);

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
        Schema::dropIfExists('people_sponsors');
    }
}
