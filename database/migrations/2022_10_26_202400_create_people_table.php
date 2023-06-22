<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('ci')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name1')->nullable();
            $table->string('last_name2')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('email')->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('phone')->nullable();

            $table->text('street')->nullable();
            $table->text('home')->nullable();
            $table->text('zone')->nullable();

            $table->text('streetB')->nullable();
            $table->text('homeB')->nullable();
            $table->text('zoneB')->nullable();

            $table->string('gender')->nullable();
            $table->string('image',600)->nullable();

            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();           

            
            $table->smallInteger('status')->default(1);
            $table->string('token')->nullable();
            $table->timestamps();
            $table->foreignId('register_userId')->nullable()->constrained('users');
            $table->softDeletes();
            $table->foreignId('deleted_userId')->nullable()->constrained('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people');
    }
}
