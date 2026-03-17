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
            $table->string('event')->nullable()->after('action');
            $table->string('type')->nullable()->after('event');
            $table->string('subject')->nullable()->after('type');
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Remove old 'type' and 'subject' columns if exist
            $table->dropColumn(['type', 'subject']);

            // Add foreign key to activity_types table
            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('activity_types')->onDelete('set null');

            // Add polymorphic subject columns
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('subject_type')->nullable();

            // Add indexes for polymorphic relation
            $table->index(['subject_id', 'subject_type']);
        });
    }
};
