<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DebtPaymentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->decimal('value', 8, 2);
            $table->tinyInteger('paid')->default(0);
            $table->date('action_date')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->decimal('value', 8, 2);
            $table->date('action_date')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('debts_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('debt_id');
            $table->integer('payment_id');
            $table->integer('value');
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
