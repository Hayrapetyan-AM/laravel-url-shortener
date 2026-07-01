<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE links MODIFY user_id BIGINT UNSIGNED NULL');
        } else {
            Schema::table('links', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            });
        }

        Schema::table('links', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE links MODIFY user_id BIGINT UNSIGNED NOT NULL');
        } else {
            Schema::table('links', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            });
        }

        Schema::table('links', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
