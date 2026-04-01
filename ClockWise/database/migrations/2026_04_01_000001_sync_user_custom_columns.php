<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'id_number')) {
                $table->string('id_number', 4)->nullable()->unique()->after('email');
            }

            if (! Schema::hasColumn('users', 'photo')) {
                $table->string('photo')->nullable()->after('id_number');
            }

            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('employee')->after('password');
            }
        });

        $users = DB::table('users')->select('id', 'id_number', 'role')->orderBy('id')->get();
        $taken = $users
            ->pluck('id_number')
            ->filter()
            ->map(fn ($value) => str_pad((string) $value, 4, '0', STR_PAD_LEFT))
            ->flip();

        foreach ($users as $user) {
            $updates = [];

            if (empty($user->id_number)) {
                $candidate = str_pad((string) ($user->id % 10000), 4, '0', STR_PAD_LEFT);
                if ($candidate === '0000') {
                    $candidate = '9999';
                }

                while ($taken->has($candidate)) {
                    $candidate = str_pad((string) ((intval($candidate) + 1) % 10000), 4, '0', STR_PAD_LEFT);
                    if ($candidate === '0000') {
                        $candidate = '9999';
                    }
                }

                $updates['id_number'] = $candidate;
                $taken->put($candidate, true);
            }

            if (empty($user->role)) {
                $updates['role'] = 'employee';
            }

            if (! empty($updates)) {
                DB::table('users')->where('id', $user->id)->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep compatibility data intact on rollback.
    }
};
