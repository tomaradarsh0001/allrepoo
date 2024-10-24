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
        Schema::table('property_type_sub_type_mapping', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('sub_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_type_sub_type_mapping', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
