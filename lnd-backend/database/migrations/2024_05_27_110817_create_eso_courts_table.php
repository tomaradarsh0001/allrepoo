<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('eso_courts', function (Blueprint $table) {
            $table->id();
            $table->string('file_no')->nullable();
            $table->foreignId('section_id')->constrained('sections')->change();
            $table->string('active_in_court')->nullable();
            $table->string('case_no');
            $table->date('commencement_date')->nullable();
            $table->string('subject');
            $table->text('status')->nullable();
            $table->date('ldoh')->nullable();
            $table->date('ndoh')->nullable();
            $table->date('closing_date')->nullable();
            $table->string('judgement_link')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('sort_order')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->string('created_by');
            $table->timestamp('updated_at')->useCurrent();
            $table->string('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('esocourt');
    }
};
