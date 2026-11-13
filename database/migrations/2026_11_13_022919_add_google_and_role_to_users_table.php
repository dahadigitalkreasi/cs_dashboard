<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')
                ->nullable()
                ->unique();

            $table->enum('role', [
                'admin',
                'cs',
                'leader_cs'
            ])->default('cs');

            $table->string('nama_cs', 100)
                ->nullable()
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['google_id']);
            $table->dropIndex(['nama_cs']);

            $table->dropColumn([
                'google_id',
                'role',
                'nama_cs'
            ]);
        });
    }
};
