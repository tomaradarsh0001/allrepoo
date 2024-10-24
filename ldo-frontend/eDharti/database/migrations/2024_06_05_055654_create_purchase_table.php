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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_id');
            $table->unsignedBigInteger('logistic_items_id');
            $table->unsignedBigInteger('category_id');
            $table->integer('purchased_unit');
            $table->date('purchased_date');
            $table->decimal('per_unit_cost', 8, 2);
            $table->decimal('total_cost');
            $table->unsignedBigInteger('vendor_supplier_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();

            //foreign key
            $table->foreign('logistic_items_id')->references('id')->on('logistic_items');
            $table->foreign('category_id')->references('id')->on('logistic_categories');
            $table->foreign('vendor_supplier_id')->references('id')->on('supplier_vendor_details');


        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['logistic_items_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['vendor_supplier_id']);
        });

        Schema::dropIfExists('purchases'); // Corrected table name to plural form 'purchases'
    }
};
