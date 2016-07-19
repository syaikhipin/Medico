<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id');
            $table->integer('user_id');
            $table->string('paypal_id');
            $table->string('paypal_plan',50);
            $table->timestamp('expires');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->foreign('user_id')
                ->references('id')
                ->on('tb_users')
                ->onDelete('cascade');
        });

        Schema::table('tb_users', function(Blueprint $table)
        {
            $table->string('payment_method');
            //if subscription ends it will be set to zero

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_users', function(Blueprint $table) {
            $table->dropColumn('payment_method');
        });
        Schema::drop('tb_payments');
    }
}
