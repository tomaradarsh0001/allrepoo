<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistic_stock_histories', function (Blueprint $table) {
            $table->unsignedInteger('last_added_units')->nullable()->change();
            $table->unsignedBigInteger('purchase_id')->nullable()->change();
            $table->date('last_added_date')->nullable()->change();
        });

        // Change action column to enum using raw SQL
        DB::statement("ALTER TABLE logistic_stock_histories MODIFY COLUMN action ENUM('issued', 'purchase', 'purchase return') DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistic_stock_histories', function (Blueprint $table) {
            $table->unsignedInteger('last_added_units')->nullable(false)->change();
            $table->unsignedBigInteger('purchase_id')->nullable(false)->change();
            $table->date('last_added_date')->nullable(false)->change();
        });

        // Revert action column to varchar using raw SQL
        DB::statement("ALTER TABLE logistic_stock_histories MODIFY COLUMN action VARCHAR(255) DEFAULT NULL");
    }
};
