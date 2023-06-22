<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoryGarment_id')->nullable()->constrained('category_garments');
            $table->foreignId('brandGarment_id')->nullable()->constrained('brand_garments');
            $table->foreignId('modelGarment_id')->nullable()->constrained('model_garments');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();

            $table->smallInteger('status')->default(1);
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
        Schema::dropIfExists('articles');
    }
}
