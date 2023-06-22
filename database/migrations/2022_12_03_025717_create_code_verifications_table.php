<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodeVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->nullable()->constrained('loans');
            $table->string('cell_phone')->nullable();
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('code_verifications');
    }
}
