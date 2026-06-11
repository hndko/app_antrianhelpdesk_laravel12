<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queue_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->nullable()->constrained('queues')->nullOnDelete();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('from_technician_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('to_technician_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();
            $table->string('action');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['queue_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_logs');
    }
};
