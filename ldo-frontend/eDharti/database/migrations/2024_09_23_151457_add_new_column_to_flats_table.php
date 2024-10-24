<?php

use Carbon\Carbon;
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
            $table->integer('property_flat_status')->after('flat_number');
            $table->string('plot_area')->nullable()->after('area_in_sqm');
            $table->string('plot_area_in_sqm')->nullable()->after('plot_area');
            $table->date('purchase_date')->after('plot_area_in_sqm')->default(Carbon::now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flats', function (Blueprint $table) {
            $table->dropColumn('property_flat_status');
            $table->dropColumn('plot_area');
            $table->dropColumn('plot_area_in_sqm');
            $table->dropColumn('purchase_date');
        });
    }
};
