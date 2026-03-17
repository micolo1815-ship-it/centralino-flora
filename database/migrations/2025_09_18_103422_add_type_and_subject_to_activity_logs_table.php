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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->nullable()->after('event');
            $table->unsignedBigInteger('subject_id')->nullable()->after('type_id');
            $table->string('subject_type')->nullable()->after('subject_id');

            // Add foreign key constraint if you have activity_types table
            $table->foreign('type_id')->references('id')->on('activity_types')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn(['type_id', 'subject_id', 'subject_type']);
        });
    }
};
