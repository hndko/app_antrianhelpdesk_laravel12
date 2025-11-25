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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->integer('queue_number')->unique(); // No. Urut
            $table->string('laptop_id'); // No. Laptop / ID
            $table->string('helpdesk_name'); // Nama Helpdesk
            // Enum status: sesuai dropdown di HTML Anda
            $table->enum('status', ['waiting', 'progress', 'done'])->default('waiting');
            $table->integer('duration_minutes')->default(60); // Durasi dalam menit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
