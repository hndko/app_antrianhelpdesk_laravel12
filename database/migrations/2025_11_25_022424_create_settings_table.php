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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_title')->default('Service Display');
            $table->string('logo_url')->nullable();

            // Video handling
            $table->string('video_url')->nullable(); // Path file atau URL YouTube
            $table->enum('video_type', ['local', 'youtube'])->default('local');

            // Marquee
            $table->text('running_text')->nullable();
            $table->integer('marquee_speed')->default(60); // Detik

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
