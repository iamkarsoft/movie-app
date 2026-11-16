<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            // Entries created by hand for titles that are not on TMDB.
            $table->boolean('is_custom')->default(false)->after('type');
            // The user who created a custom entry (null for TMDB titles).
            $table->foreignUuid('created_by')->nullable()->after('is_custom');
            $table->index(['is_custom', 'created_by']);
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropIndex(['is_custom', 'created_by']);
            $table->dropColumn(['is_custom', 'created_by']);
        });
    }
};
