<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::table('logistic_request_items', function (Blueprint $table) {
            $table->integer('issued_units')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('logistic_request_items', function (Blueprint $table) {
            $table->integer('issued_units')->nullable(false)->change();
        });
    }
};
