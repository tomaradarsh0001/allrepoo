<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logistic_available_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('logistic_items_id');
            $table->integer('available_units');
            $table->integer('used_units');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();

            //foreign key
            $table->foreign('logistic_items_id')->references('id')->on('logistic_items');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('logistic_available_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('logistic_items_id');
            $table->integer('available_units');
            $table->integer('used_units');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();

            //foreign key
            $table->foreign('logistic_items_id')->references('id')->on('logistic_items');

        });
    }
};
