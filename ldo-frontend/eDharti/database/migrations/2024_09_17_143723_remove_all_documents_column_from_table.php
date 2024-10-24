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
            $table->dropColumn('document_one'); // Remove the column
            $table->dropColumn('document_two'); // Remove the column
            $table->dropColumn('document_three'); // Remove the column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flats', function (Blueprint $table) {
            $table->string('document_one');
            $table->string('document_two');
            $table->string('document_three');
        });
    }
};
