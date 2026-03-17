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
        Schema::create('trees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['active', 'archive'])->default('active');
            $table->string('scientific_name')->nullable();
            $table->string('common_name')->nullable();
            $table->string('local_name')->nullable();
            $table->longText('description')->nullable();
            $table->longText('uses_filipino')->nullable();
            $table->text('tree_facts')->nullable();
            $table->text('tagged_trees')->nullable();
            $table->string('domain')->nullable();
            $table->string('kingdom')->nullable();
            $table->string('phylum')->nullable();
            $table->string('class')->nullable();
            $table->string('order')->nullable();
            $table->string('family')->nullable();
            $table->string('genus')->nullable();
            $table->string('species')->nullable();
            $table->json('cover_image')->nullable();
            $table->json('image_gallery')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trees');
    }
};
