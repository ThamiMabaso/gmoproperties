<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Align legacy `announcements` tables with the schema expected by the app
     * (e.g. environments where the table existed before publish/published columns were added).
     */
    public function up(): void
    {
        if (! Schema::hasTable('announcements')) {
            return;
        }

        if (! Schema::hasColumn('announcements', 'is_published')) {
            Schema::table('announcements', function (Blueprint $table): void {
                $table->boolean('is_published')->default(false);
            });
        }

        if (! Schema::hasColumn('announcements', 'publish_at')) {
            Schema::table('announcements', function (Blueprint $table): void {
                $table->timestamp('publish_at')->nullable();
            });
        }

        if (! Schema::hasColumn('announcements', 'published_at')) {
            Schema::table('announcements', function (Blueprint $table): void {
                $table->timestamp('published_at')->nullable();
            });
        }

        if (! Schema::hasColumn('announcements', 'expires_at')) {
            Schema::table('announcements', function (Blueprint $table): void {
                $table->timestamp('expires_at')->nullable();
            });
        }

        if (! Schema::hasColumn('announcements', 'priority')) {
            Schema::table('announcements', function (Blueprint $table): void {
                $table->unsignedTinyInteger('priority')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
