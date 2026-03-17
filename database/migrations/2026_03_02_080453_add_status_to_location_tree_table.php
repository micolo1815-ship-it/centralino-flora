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
        Schema::table('location_tree', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->after('tree_id'); // 1=Active, 0=Inactive
        });
    }

    public function down()
    {
        Schema::table('location_tree', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
