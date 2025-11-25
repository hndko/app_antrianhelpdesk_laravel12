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
        Schema::table('queues', function (Blueprint $table) {
            $table->dropColumn('helpdesk_name');
            $table->foreignId('technician_id')->nullable()->constrained('technicians');
            $table->enum('status', ['waiting', 'progress', 'done', 'completed'])->default('waiting')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->dropForeign(['technician_id']);
            $table->dropColumn('technician_id');
            $table->string('helpdesk_name');
            $table->enum('status', ['waiting', 'progress', 'done'])->default('waiting')->change();
        });
    }
};
