<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_campaign', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique()->comment('Slug is gonna be used in campaign link');
            $table->timestamp('start_at')->nullable()->index();
            $table->timestamp('end_at')->nullable()->index();
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
        Schema::table('m_campaign', function (Blueprint $table) {
            $table->dropUnique('m_campaign_name_unique');
            $table->dropUnique('m_campaign_slug_unique');
            $table->dropIndex('m_campaign_start_at_index');
            $table->dropIndex('m_campaign_end_at_index');
        });
        Schema::dropIfExists('m_campaign');
    }
}
