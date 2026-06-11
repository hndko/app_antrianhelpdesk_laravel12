<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('queues')
            ->where('status', 'completed')
            ->update(['status' => 'done']);

        Schema::table('queues', function (Blueprint $table) {
            if (Schema::hasColumn('queues', 'technician_id')) {
                $table->dropForeign(['technician_id']);
                $table->dropColumn('technician_id');
            }

            $table->index(['status', 'updated_at']);
            $table->index(['technician_user_id', 'status']);
            $table->enum('status', ['waiting', 'progress', 'done'])->default('waiting')->change();
        });

        Schema::dropIfExists('technicians');
    }

    public function down(): void
    {
        Schema::create('technicians', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('queues', function (Blueprint $table) {
            if (! Schema::hasColumn('queues', 'technician_id')) {
                $table->foreignId('technician_id')->nullable()->after('technician_user_id');
            }

            $table->dropIndex(['status', 'updated_at']);
            $table->dropIndex(['technician_user_id', 'status']);
            $table->enum('status', ['waiting', 'progress', 'done', 'completed'])->default('waiting')->change();
        });
    }
};
