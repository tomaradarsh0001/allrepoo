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
        Schema::table('flats', function (Blueprint $table) {
            $table->unsignedBigInteger('locality')->nullable()->change();
            $table->string('block')->nullable()->change();
            $table->string('plot')->nullable()->change();
            $table->string('known_as')->nullable()->change();
            $table->string('flat_number')->nullable()->change();
            $table->string('area')->nullable()->change();
            $table->string('unit')->nullable()->change();
            $table->string('area_in_sqm')->nullable()->change();
            $table->string('property_flat_status')->nullable()->change();
            $table->string('builder_developer_name')->nullable()->change();
            $table->string('original_buyer_name')->nullable()->change();
            $table->date('purchase_date')->nullable()->change();
            $table->string('present_occupant_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flats', function (Blueprint $table) {
            $table->unsignedBigInteger('locality')->nullable(false)->change();
            $table->string('block')->nullable(false)->change();
            $table->string('plot')->nullable(false)->change();
            $table->string('known_as')->nullable(false)->change();
            $table->string('flat_number')->nullable(false)->change();
            $table->string('area')->nullable(false)->change();
            $table->string('unit')->nullable(false)->change();
            $table->string('area_in_sqm')->nullable(false)->change();
            $table->string('property_flat_status')->nullable(false)->change();
            $table->string('builder_developer_name')->nullable(false)->change();
            $table->string('original_buyer_name')->nullable(false)->change();
            $table->date('purchase_date')->nullable(false)->change(); // Correcting the nullable to nullable(false)
            $table->string('present_occupant_name')->nullable(false)->change();
        });
    }
};
