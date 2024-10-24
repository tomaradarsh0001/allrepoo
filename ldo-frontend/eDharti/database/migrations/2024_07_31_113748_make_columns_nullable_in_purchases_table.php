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
        Schema::table('purchases', function (Blueprint $table) {
            $table->integer('purchased_unit')->nullable()->change();
            $table->decimal('per_unit_cost')->nullable()->change();
            $table->decimal('total_cost')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->integer('purchased_unit')->nullable(false)->change();
            $table->decimal('per_unit_cost')->nullable(false)->change();
            $table->decimal('total_cost')->nullable(false)->change();
        });
    }
};
