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
        Schema::table('current_lessee_details', function (Blueprint $table) {
            $table->unsignedBigInteger('flat_id')->nullable()->after('old_property_id');
            $table->foreign('flat_id')->references('id')->on('flats')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('current_lessee_details', function (Blueprint $table) {
            $table->dropForeign(['flat_id']);
        });
    }
};
