<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteCollectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_collectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->nullable()->constrained('routes');
            $table->foreignId('user_id')->nullable()->constrained('users');

            $table->text('observation')->nullable();

            $table->smallInteger('status')->default(1);
            $table->timestamps();
            $table->foreignId('register_userId')->nullable()->constrained('users');

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
        Schema::dropIfExists('route_collectors');
    }
}
