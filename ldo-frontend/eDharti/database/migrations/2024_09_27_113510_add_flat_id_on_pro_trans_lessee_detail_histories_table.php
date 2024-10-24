<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pro_trans_lessee_detail_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('flat_id')->after('splited_property_detail_id')->nullable();
            $table->foreign('flat_id')->references('id')->on('flats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pro_trans_lessee_detail_histories', function (Blueprint $table) {
            $table->dropColumn('flat_id');
        });
    }
};
