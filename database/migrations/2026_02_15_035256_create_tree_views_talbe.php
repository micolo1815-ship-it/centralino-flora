<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tree_views', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tree_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();

            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            $table->date('view_date'); // KEY for daily/monthly/yearly tracking

            $table->timestamps();

            // Prevent spam (1 view per IP per day per tree per location)
            $table->unique([
                'tree_id',
                'location_id',
                'ip_address',
                'view_date'
            ], 'unique_daily_tree_view');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tree_views_talbe');
    }
};
