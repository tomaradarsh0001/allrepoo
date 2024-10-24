<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('logistic_request_items', function (Blueprint $table) {
            $table->string('request_id')->after('id');
            $table->unsignedBigInteger('category_id')->after('logistic_items_id');
            $table->bigInteger('available_units')->after('category_id');
            $table->bigInteger('issued_units')->after('requested_units');
            $table->integer('available_after_request')->after('issued_units')->change();

            $table->foreign('category_id')->references('id')->on('logistic_categories');
        });
    }

    public function down()
    {
        Schema::table('logistic_request_items', function (Blueprint $table) {
            $table->dropColumn('category_id');
            $table->dropColumn('request_id');
            $table->dropColumn('available_units');
            $table->dropColumn('issued_units');
            $table->integer('available_after_request')->after('logistic_items_id')->change();
        });
    }
};
