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
        Schema::create('flats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_master_id');
            $table->bigInteger('old_property_id')->nullable();
            $table->string('unique_property_id')->nullable();
            $table->unsignedBigInteger('splited_property_id')->nullable();
            $table->unsignedBigInteger('locality');
            $table->integer('block');
            $table->integer('plot');
            $table->string('known_as');
            $table->string('flat_number');
            $table->string('area');
            $table->string('unit');
            $table->string('area_in_sqm');
            $table->string('original_buyer_name');
            $table->string('present_occupant_name');
            $table->string('builder_developer_name');
            $table->string('document_one')->nullable();
            $table->string('document_two')->nullable();
            $table->string('document_three')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            // Foreign key relationships
            $table->foreign('property_master_id')->references('id')->on('property_masters')->onDelete('cascade');
            $table->foreign('splited_property_id')->references('id')->on('splited_property_details')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('locality')->references('id')->on('old_colonies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flats');
    }
};
