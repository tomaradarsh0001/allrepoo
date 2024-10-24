<?php

use Carbon\Carbon;
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
        Schema::create('flat_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flat_id');
            $table->unsignedBigInteger('property_master_id');
            $table->unsignedBigInteger('new_property_master_id');
            $table->unsignedBigInteger('splited_property_id')->nullable();
            $table->unsignedBigInteger('new_splited_property_id')->nullable();
            $table->unsignedBigInteger('locality')->nullable();
            $table->unsignedBigInteger('new_locality')->nullable();
            $table->integer('block')->nullable();
            $table->integer('new_block')->nullable();
            $table->integer('plot')->nullable();
            $table->integer('new_plot')->nullable();
            $table->string('known_as')->nullable();
            $table->string('new_known_as')->nullable();
            $table->string('unique_file_no')->nullable();
            $table->string('new_unique_file_no')->nullable();
            $table->string('flat_number')->nullable();
            $table->string('new_flat_number')->nullable();
            $table->string('area')->nullable();
            $table->string('new_area')->nullable();
            $table->string('unit')->nullable();
            $table->string('new_unit')->nullable();
            $table->string('area_in_sqm')->nullable();
            $table->string('new_area_in_sqm')->nullable();
            $table->integer('property_flat_status')->nullable();
            $table->integer('new_property_flat_status')->nullable();
            $table->string('builder_developer_name')->nullable();
            $table->string('new_builder_developer_name')->nullable();
            $table->string('original_buyer_name')->nullable();
            $table->string('new_original_buyer_name')->nullable();
            $table->date('purchase_date')->default(Carbon::now());
            $table->date('new_purchase_date')->default(Carbon::now());
            $table->string('present_occupant_name')->nullable();
            $table->string('new_present_occupant_name')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable()->nullable();
            // Foreign key relationships
            $table->foreign('flat_id')->references('id')->on('flats')->onDelete('cascade');
            $table->foreign('new_property_master_id')->references('id')->on('property_masters')->onDelete('cascade');
            $table->foreign('property_master_id')->references('id')->on('property_masters')->onDelete('cascade');
            $table->foreign('new_splited_property_id')->references('id')->on('splited_property_details')->onDelete('cascade');
            $table->foreign('splited_property_id')->references('id')->on('splited_property_details')->onDelete('cascade');
            $table->foreign('new_locality')->references('id')->on('old_colonies')->onDelete('cascade');
            $table->foreign('locality')->references('id')->on('old_colonies')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flat_histories');
    }
};
