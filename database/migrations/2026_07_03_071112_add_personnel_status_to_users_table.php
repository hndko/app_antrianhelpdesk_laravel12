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
        Schema::table('users', function (Blueprint $table) {
            $table->string('personnel_status')->default('ready')->after('role');
            $table->string('status_estimated_time', 50)->nullable()->after('personnel_status');
            $table->string('status_note', 100)->nullable()->after('status_estimated_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['personnel_status', 'status_estimated_time', 'status_note']);
        });
    }
};
