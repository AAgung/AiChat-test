<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTPurchaseTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_purchase_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->decimal('total_spent', 10, 2)->default(0.00)->index();
            $table->decimal('total_saving', 10, 2)->default(0.00)->index();
            $table->timestamp('transaction_at')->index();

            $table->foreign('customer_id')->references('id')->on('m_customer')->onDelete('cascade');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_purchase_transaction', function (Blueprint $table) {
           $table->dropIndex('t_purchase_transaction_total_spent_index'); 
           $table->dropIndex('t_purchase_transaction_total_saving_index'); 
           $table->dropIndex('t_purchase_transaction_transaction_at_index'); 
           $table->dropForeign('t_purchase_transaction_customer_id_foreign'); 
        });
        Schema::dropIfExists('t_purchase_transaction');
    }
}
