<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::table('movie_user', function (Blueprint $table) {
                $table->unsignedInteger('season')->nullable();
                $table->unsignedInteger('episode')->nullable();
            });
        }

        public function down(): void
        {
            Schema::table('movie_user', function (Blueprint $table) {
                $table->dropColumn('season');
                $table->dropColumn('episode');
            });
        }
    };
