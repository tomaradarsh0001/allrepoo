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
        Schema::table('logistic_request_items', function (Blueprint $table) {
            $table->unsignedBigInteger('updated_by')->nullable()->change();
        });

        // Change enum values using raw SQL
        DB::statement("ALTER TABLE logistic_request_items MODIFY COLUMN status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistic_request_items', function (Blueprint $table) {
            $table->unsignedBigInteger('updated_by')->nullable(false)->change();
        });

        // Revert enum values using raw SQL
        DB::statement("ALTER TABLE logistic_request_items MODIFY COLUMN status ENUM('Pending', 'issued', 'cancelled') DEFAULT 'Pending'");
    }
};
