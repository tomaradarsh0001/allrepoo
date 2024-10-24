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
            $table->string('new_property_master_id')->nullable()->after('property_master_id');
            $table->string('new_splited_property_detail_id')->nullable()->after('splited_property_detail_id');
            $table->string('plot_flat_no')->nullable()->after('flat_id');
            $table->string('new_plot_flat_no')->nullable()->after('plot_flat_no');
            $table->string('old_property_id')->nullable()->after('new_plot_flat_no');
            $table->string('new_old_property_id')->nullable()->after('old_property_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pro_trans_lessee_detail_histories', function (Blueprint $table) {
            $table->dropColumn('new_property_master_id');
            $table->dropColumn('new_splited_property_detail_id');
            $table->dropColumn('plot_flat_no');
            $table->dropColumn('new_plot_flat_no');
            $table->dropColumn('old_property_id');
            $table->dropColumn('new_old_property_id');
        });
    }
};
