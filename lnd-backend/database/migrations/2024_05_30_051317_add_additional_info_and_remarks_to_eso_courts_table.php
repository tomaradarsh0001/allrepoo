<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('eso_courts', function (Blueprint $table) {
            //
            $table->string('additional_info')->nullable()->after('judgement_link');
            $table->string('remarks')->nullable()->after('additional_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('eso_courts', function (Blueprint $table) {
            //
            $table->dropColumn('additional_info');
            $table->dropColumn('remarks');
        });
    }
};
