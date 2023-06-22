<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->foreignId('people_id')->nullable()->constrained('people');
            $table->foreignId('article_id')->nullable()->constrained('articles');
            $table->foreignId('categoryGarment_id')->nullable()->constrained('category_garments');
            $table->foreignId('brandGarment_id')->nullable()->constrained('brand_garments');
            $table->foreignId('modelGarment_id')->nullable()->constrained('model_garments');

            $table->string('article')->nullable();
            $table->string('categoryGarment')->nullable();
            $table->string('brandGarment')->nullable();
            $table->string('modelGarment')->nullable();

            $table->text('articleDescription')->nullable();
            
            $table->foreignId('cashier_id')->nullable()->constrained('cashiers');

            $table->string('type')->nullable();
            $table->date('date')->nullable();

            $table->decimal('amountLoan',11,2)->nullable();
            $table->decimal('amountLoanDollar',11,2)->nullable();
            $table->decimal('priceDollar',11,2)->nullable();


            $table->decimal('amountPorcentage',11,2)->nullable();
            $table->decimal('porcentage',11,2)->nullable();

            $table->decimal('amountTotal',11,2)->nullable();

            $table->integer('cantMonth')->nullable();

            $table->text('observation')->nullable();//Para las observacones en general
            $table->string('status')->nullable('pendiente');

            // Para saber quien lo entrega el dinero o el prendario
            $table->string('delivered')->default('No');
            $table->date('dateDelivered')->nullable();
            $table->foreignId('delivered_userId')->nullable()->constrained('users');
            $table->string('delivered_agentType')->nullable();

            // $table->string('transaction_id')->nullable();

            // Para saber quien aprueba el registro de la prenda
            $table->foreignId('success_userId')->nullable()->constrained('users');
            $table->string('success_agentType')->nullable();

            // Para saber con que caja fue registrada la prenda
            $table->foreignId('cashierRegister_id')->nullable()->constrained('cashiers');
            $table->foreignId('register_userId')->nullable()->constrained('users');
            $table->string('register_agentType')->nullable();



            // $table->date('notificationDate')->default(date('Y-m-d'));
            // $table->bigInteger('notificationQuantity')->default(0);
            $table->timestamps();

            // Para saber quien elimina
            $table->foreignId('deleted_userId')->nullable()->constrained('users');
            $table->string('deleted_agentType')->nullable();
            $table->text('deleteObservation')->nullable();
            $table->string('deletedKey')->nullable();
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
        Schema::dropIfExists('garments');
    }
}
