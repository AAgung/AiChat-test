<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMCampaignVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_campaign_voucher', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->string('code', 10)->unique();
            $table->decimal('value', 10, 2)->default(0.00);
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->timestamp('lockdown_at')->nullable()->index();
            $table->timestamp('lockdown_expired_at')->nullable()->index();
            $table->boolean('is_qualified')->default(0)->index();
            $table->timestamps();

            $table->foreign('campaign_id')->references('id')->on('m_campaign')->onDelete('cascade');
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
        Schema::table('m_campaign_voucher', function (Blueprint $table) {
            $table->dropUnique('m_campaign_voucher_code_unique');
            $table->dropIndex('m_campaign_voucher_lockdown_at_index');
            $table->dropIndex('m_campaign_voucher_lockdown_expired_at_index');
            $table->dropIndex('m_campaign_voucher_is_qualified_index');
            $table->dropForeign('m_campaign_voucher_campaign_id_foreign');
            $table->dropForeign('m_campaign_voucher_customer_id_foreign');
        });
        Schema::dropIfExists('m_campaign_voucher');
    }
}
