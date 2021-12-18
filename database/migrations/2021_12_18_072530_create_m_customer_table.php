<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_customer', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->index();
            $table->string('last_name')->nullable()->index();
            $table->string('gender', 50)->nullable();
            $table->date('date_of_birth')->nullable()->index();
            $table->string('contact_number', 50)->nullable()->index();
            $table->string('email')->unique();
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
        Schema::table('m_customer', function (Blueprint $table) {
            $table->dropIndex('m_customer_first_name_index');
            $table->dropIndex('m_customer_last_name_index');
            $table->dropIndex('m_customer_date_of_birth_index');
            $table->dropIndex('m_customer_contact_number_index');
            $table->dropUnique('m_customer_email_unique');
        });
        Schema::dropIfExists('m_customer');
    }
}
