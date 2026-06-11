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
        if (Schema::hasColumn('settings', 'favicon_url')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->string('favicon_url')->nullable()->after('logo_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('settings', 'favicon_url')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('favicon_url');
        });
    }
};
