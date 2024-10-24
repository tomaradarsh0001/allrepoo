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
        Schema::create('land_use_change_matrix', function (Blueprint $table) {
            $table->id();
            $table->integer('property_type_from')->nullable();
            $table->integer('property_sub_type_from')->nullable();
            $table->integer('property_type_to')->nullable();
            $table->integer('property_sub_type_to')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->decimal('rate', 8, 5);
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('land_use_change_matrix');
    }
};
