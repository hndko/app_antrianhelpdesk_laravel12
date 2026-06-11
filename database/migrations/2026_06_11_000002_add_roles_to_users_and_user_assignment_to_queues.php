<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('service_desk')->after('password')->index();
            }

            if (! Schema::hasColumn('users', 'status')) {
                $table->boolean('status')->default(true)->after('role')->index();
            }
        });

        DB::table('users')
            ->where('username', 'helpdesk')
            ->update(['role' => 'superadmin', 'status' => true]);

        if (Schema::hasTable('technicians')) {
            $technicians = DB::table('technicians')->get();

            foreach ($technicians as $technician) {
                $username = $this->uniqueValue('users', 'username', Str::slug($technician->name, '') ?: 'technician'.$technician->id);
                $emailPrefix = Str::slug($technician->name, '.') ?: 'technician'.$technician->id;
                $email = $this->uniqueValue('users', 'email', $emailPrefix.'@example.com');

                $existingUser = DB::table('users')
                    ->where('name', $technician->name)
                    ->where('role', 'technician')
                    ->first();

                if ($existingUser) {
                    continue;
                }

                DB::table('users')->insert([
                    'name' => $technician->name,
                    'username' => $username,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'technician',
                    'status' => (bool) ($technician->status ?? true),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('queues', function (Blueprint $table) {
            if (! Schema::hasColumn('queues', 'technician_user_id')) {
                $table->foreignId('technician_user_id')
                    ->nullable()
                    ->after('laptop_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });

        if (Schema::hasTable('technicians') && Schema::hasColumn('queues', 'technician_id')) {
            $queues = DB::table('queues')
                ->whereNotNull('technician_id')
                ->whereNull('technician_user_id')
                ->get(['id', 'technician_id']);

            foreach ($queues as $queue) {
                $technician = DB::table('technicians')->find($queue->technician_id);

                if (! $technician) {
                    continue;
                }

                $user = DB::table('users')
                    ->where('name', $technician->name)
                    ->where('role', 'technician')
                    ->first();

                if ($user) {
                    DB::table('queues')
                        ->where('id', $queue->id)
                        ->update(['technician_user_id' => $user->id]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            if (Schema::hasColumn('queues', 'technician_user_id')) {
                $table->dropForeign(['technician_user_id']);
                $table->dropColumn('technician_user_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }

    private function uniqueValue(string $table, string $column, string $value): string
    {
        $base = $value;
        $counter = 2;

        while (DB::table($table)->where($column, $value)->exists()) {
            $value = $base.$counter;
            $counter++;
        }

        return $value;
    }
};
