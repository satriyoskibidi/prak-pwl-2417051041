<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->uuid('new_id')->nullable();
        });

        DB::table('user')->orderBy('id')->chunkById(500, function ($users) {
            foreach ($users as $user) {
                DB::table('user')->where('id', $user->id)->update(['new_id' => (string) Str::uuid()]);
            }
        });

        Schema::table('user', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
            $table->renameColumn('new_id', 'id');
            $table->primary('id');
        });

        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->renameColumn('uuid', 'id');
            $table->primary('id');
        });
    }

    public function down(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropPrimary();
            $table->renameColumn('id', 'uuid');
        });

        Schema::table('user', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
            $table->id();
        });
    }
};
