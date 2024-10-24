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
        Schema::create('page_section', function (Blueprint $table) {
            $table->id();
            $table->integer('page_id');
            $table->integer('parent_id')->default(null);
            $table->string('heading_eng', 255);
            $table->string('heading_hin', 255);
            $table->string('content_eng', 2000);
            $table->string('content_hin', 2000);
            $table->string('key', 255);
            $table->integer('order')->default(null);
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_section');
    }
};
