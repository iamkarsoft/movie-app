<?php

use App\Models\Movie;
use App\Models\MovieUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Step 1: Merge duplicate movies records (same name).
        // Keep the oldest record, redirect all movie_user rows to it, delete the rest.
        $duplicateNames = Movie::select('name')
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('name');

        foreach ($duplicateNames as $name) {
            $movies = Movie::where('name', $name)->orderBy('created_at')->get();
            $keep = $movies->first();

            foreach ($movies->skip(1) as $duplicate) {
                MovieUser::where('movie_id', $duplicate->id)
                    ->update(['movie_id' => $keep->id]);
                $duplicate->delete();
            }
        }

        // Step 2: Remove duplicate movie_user rows (same movie_id + user_id).
        // Keep the most recently updated entry.
        $duplicatePivots = DB::table('movie_user')
            ->select('movie_id', 'user_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('movie_id', 'user_id')
            ->having('cnt', '>', 1)
            ->get();

        foreach ($duplicatePivots as $pivot) {
            $ids = MovieUser::where('movie_id', $pivot->movie_id)
                ->where('user_id', $pivot->user_id)
                ->orderByDesc('updated_at')
                ->pluck('id');

            MovieUser::whereIn('id', $ids->skip(1))->delete();
        }

        // Step 3: Add unique constraint to prevent future duplicates.
        Schema::table('movie_user', function (Blueprint $table) {
            $table->unique(['movie_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('movie_user', function (Blueprint $table) {
            $table->dropUnique(['movie_id', 'user_id']);
        });
    }
};
