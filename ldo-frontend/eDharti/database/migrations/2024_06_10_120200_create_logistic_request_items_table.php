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
        Schema::create('logistic_request_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('logistic_items_id');
            $table->integer('available_units');
            $table->unsignedBigInteger('requested_units');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'issued', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');

            //foreign key
            $table->foreign('logistic_items_id')->references('id')->on('logistic_items');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistic_request_items');
    }
};
