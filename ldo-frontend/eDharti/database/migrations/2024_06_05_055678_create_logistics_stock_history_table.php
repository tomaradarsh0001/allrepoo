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
        Schema::create('logistic_stock_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('logistic_items_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('purchase_id');
            $table->integer('available_units');
            $table->integer('last_units');
            $table->integer('last_added_units');
            $table->date('last_added_date');
            $table->integer('last_reduced_units')->nullable();
            $table->date('last_reduced_date')->nullable();
            $table->integer('issued_units')->nullable();
            $table->unsignedBigInteger('issued_to_user_id')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->string('action')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();

            //foreign key
            $table->foreign('logistic_items_id')->references('id')->on('logistic_items');
            $table->foreign('category_id')->references('id')->on('logistic_categories');
            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->foreign('issued_to_user_id')->references('id')->on('users');
            $table->foreign('issued_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistic_stock_histories');
    }
};
