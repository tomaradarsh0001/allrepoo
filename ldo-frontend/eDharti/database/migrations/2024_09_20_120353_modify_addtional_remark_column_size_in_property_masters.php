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
        Schema::table('property_masters', function (Blueprint $table) {
            // Modify the column "addtional_remark" size (e.g., change length to 1000
            $table->string('additional_remark', 1020)->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_masters', function (Blueprint $table) {
            //
        });
    }
};
