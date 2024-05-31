<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('web_forms', function (Blueprint $table) {
            $table->tinyInteger('create_driver')->default(0)->after('create_lead');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('web_forms', function (Blueprint $table) {
            $table->dropIfExists('create_driver');
        });
    }
};
