<div>
    <div class="container mx-auto px-4 pt-12 pb-16">

        <div class="mb-10">
            <h1 class="text-3xl font-bold text-white">Browse</h1>
            <p class="text-zinc-400 mt-1">Popular movies, upcoming releases, and on-air series</p>
        </div>

        <section class="mb-14">
            <div class="flex items-center mb-6">
                <h2 class="text-lg font-semibold text-white uppercase tracking-widest shrink-0">Popular Movies</h2>
                <div class="h-px flex-1 bg-zinc-800 ml-4"></div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
                @foreach($popularMovies as $movie)
                    <x-movie-card :movie="$movie" :genres="$genres" />
                @endforeach
            </div>
        </section>

        <section class="mb-14">
            <div class="flex items-center mb-6">
                <h2 class="text-lg font-semibold text-white uppercase tracking-widest shrink-0">Upcoming Movies</h2>
                <div class="h-px flex-1 bg-zinc-800 ml-4"></div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
                @foreach($upcomingMovies as $movie)
                    <x-movie-card :movie="$movie" :genres="$genres" />
                @endforeach
            </div>
        </section>

        <section>
            <div class="flex items-center mb-6">
                <h2 class="text-lg font-semibold text-white uppercase tracking-widest shrink-0">Series</h2>
                <div class="h-px flex-1 bg-zinc-800 ml-4"></div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
                @foreach($tvShows as $tv)
                    <x-tv-card :tv="$tv" :tvGenres="$tvGenres" />
                @endforeach
            </div>
        </section>

    </div>
</div>
