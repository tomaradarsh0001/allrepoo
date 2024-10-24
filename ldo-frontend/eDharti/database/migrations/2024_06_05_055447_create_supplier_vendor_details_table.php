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
        Schema::create('supplier_vendor_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_no');
            $table->string('email')->unique();
            $table->text('office_address');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('is_tender', ['active', 'inactive'])->default('active');
            $table->date('from_tender');
            $table->date('to_tender');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('supplier_vendor_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_no');
            $table->string('email')->unique();
            $table->text('office_address');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('is_tender', ['active', 'inactive'])->default('active');
            $table->date('from_tender');
            $table->date('to_tender');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
        });
    }
};
