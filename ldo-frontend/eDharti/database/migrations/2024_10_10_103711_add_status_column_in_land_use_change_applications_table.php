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
        Schema::table('land_use_change_applications', function (Blueprint $table) {
            $table->integer('status')->nullable()->after('application_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('land_use_change_applications', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};