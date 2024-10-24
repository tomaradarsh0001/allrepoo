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
        Schema::table('logistic_stock_histories', function (Blueprint $table) {
            $table->integer('reduced_unit')->after('available_units');
            $table->string('purchase_unique_id')->after('purchase_id');
            $table->string('request_unique_id')->after('request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logistic_stock_histories', function (Blueprint $table) {
            $table->dropColumn('reduced_unit');
            $table->dropColumn('purchase_unique_id');
            $table->dropColumn('request_unique_id');
        });
    }
};
