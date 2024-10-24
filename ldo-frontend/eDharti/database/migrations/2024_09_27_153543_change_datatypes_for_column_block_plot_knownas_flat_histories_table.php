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
        Schema::table('flat_histories', function (Blueprint $table) {
            $table->string('block', 255)->change();
            $table->string('new_block', 255)->change();
            $table->string('plot', 255)->change();
            $table->string('new_plot', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flat_histories', function (Blueprint $table) {
            $table->integer('block')->change();
            $table->integer('new_block')->change();
            $table->integer('plot')->change();
            $table->integer('new_plot')->change();
        });
    }
};
