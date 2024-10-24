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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demand_id');
            $table->foreignId('property_master_id');
            $table->foreignId('splited_property_detail_id')->nullable();
            $table->integer('master_old_property_id');
            $table->integer('splited_old_property_id')->nullable();
            $table->decimal('amount', total: 12, places: 2)->default(0.00);
            $table->string('reference_no');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
