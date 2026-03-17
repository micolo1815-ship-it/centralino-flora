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
        Schema::table('password_reset_otps', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('expires_at');
            $table->string('device')->nullable()->after('ip_address');
            $table->text('user_agent')->nullable()->after('device');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_otps', function (Blueprint $table) {
            //
        });
    }
};
