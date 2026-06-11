<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('queue_logs', function (Blueprint $table) {
            $table->integer('queue_number')->nullable()->after('note');
            $table->string('user_name')->nullable()->after('queue_number');
            $table->string('laptop_id')->nullable()->after('user_name');
        });
    }

    public function down(): void
    {
        Schema::table('queue_logs', function (Blueprint $table) {
            $table->dropColumn(['queue_number', 'user_name', 'laptop_id']);
        });
    }
};
