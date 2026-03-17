<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add officer_id column (nullable for existing data)
            $table->foreignId('officer_id')->nullable()->after('position');  // Or after 'email'
            
            // Add foreign key constraint (references officers.id)
            $table->foreign('officer_id')->references('id')->on('officers')->onUpdate('cascade')->onDelete('set null');
            // ^ 'set null' means if officer deleted, user.officer_id = null (retain user)
            // Alternative: 'restrict' to prevent officer delete if user linked
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key first, then column
            $table->dropForeign(['officer_id']);
            $table->dropColumn('officer_id');
        });
    }
};
