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
        // Schema::table('flats', function (Blueprint $table) {
        //     // First, drop the columns you want to reorder
        //     $table->dropColumn([
        //         'area',
        //         'unit',
        //         'area_in_sqm',
        //         'property_flat_status',
        //         'builder_developer_name',
        //         'original_buyer_name',
        //         'purchase_date',
        //         'present_occupant_name',
        //         'plot_area',
        //         'plot_area_in_sqm'
        //     ]);
        // });

        Schema::table('flats', function (Blueprint $table) {
            // Re-add them in the desired order
            $table->string('area')->after('flat_number');
            $table->string('unit')->after('area');
            $table->string('area_in_sqm')->after('unit');
            $table->string('property_flat_status')->after('area_in_sqm');
            $table->string('builder_developer_name')->after('property_flat_status');
            $table->string('original_buyer_name')->after('builder_developer_name');
            $table->date('purchase_date')->after('original_buyer_name')->nullable();
            $table->string('present_occupant_name')->after('purchase_date');
            $table->string('plot_area')->after('present_occupant_name');
            $table->string('plot_area_in_sqm')->after('plot_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flats', function (Blueprint $table) {
            // Drop the columns again in reverse order
            $table->dropColumn([
                'area',
                'unit',
                'area_in_sqm',
                'property_flat_status',
                'builder_developer_name',
                'original_buyer_name',
                'purchase_date',
                'present_occupant_name',
                'plot_area',
                'plot_area_in_sqm'
            ]);
        });

        Schema::table('flats', function (Blueprint $table) {
            // Re-add the columns back in the original order
            $table->string('area');
            $table->string('unit');
            $table->string('area_in_sqm');
            $table->string('property_flat_status');
            $table->string('builder_developer_name');
            $table->string('original_buyer_name');
            $table->date('purchase_date');
            $table->string('present_occupant_name');
            $table->string('plot_area');
            $table->string('plot_area_in_sqm');
        });
    }
};
