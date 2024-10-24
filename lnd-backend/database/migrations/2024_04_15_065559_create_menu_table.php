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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('label_eng', 255);
            $table->string('label_hin', 255);
            $table->integer('parent_id')->default(null);
            $table->string('link', 255)->default(null);
            $table->enum('location', ['haeder', 'footer', 'page']);
            $table->integer('sort_order')->default(null);
            $table->bigInteger('page_id')->default(null);
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
