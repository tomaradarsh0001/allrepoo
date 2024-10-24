<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->date('display_till')->nullable()->after('is_active');
        });
    }
    
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('display_till');
        });
    }
    
};
