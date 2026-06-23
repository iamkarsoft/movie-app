@extends('layout.app')
@section('content')

{{-- Hero --}}
<div class="relative overflow-hidden" style="height: 420px;">
    @if(!empty($tv['backdrop_path']))
        <img src="https://image.tmdb.org/t/p/original/{{ $tv['backdrop_path'] }}"
             alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:top;">
    @endif
    <div style="position:absolute;inset:0;background:linear-gradient(to top, #111827 0%, rgba(17,24,39,0.5) 50%, transparent 100%);"></div>
    <div style="position:absolute;inset:0;background:linear-gradient(to right, rgba(17,24,39,0.8) 0%, transparent 60%);"></div>

    {{-- Title over hero --}}
    <div class="absolute bottom-0 left-0 right-0 container mx-auto px-4 pb-6">
        <h1 class="text-3xl md:text-5xl font-bold text-white drop-shadow-lg">{{ $tv['name'] }}</h1>
        <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-zinc-300">
            <span class="text-yellow-400 font-semibold">★ {{ number_format($tv['vote_average'] ?? 0, 1) }}</span>
            @if(!empty($tv['first_air_date']))
                <span>·</span>
                <span>{{ \Carbon\Carbon::parse($tv['first_air_date'])->format('M d, Y') }}</span>
            @endif
            @if(!empty($tv['genres']))
                <span>·</span>
                <span>{{ collect($tv['genres'])->pluck('name')->join(', ') }}</span>
            @endif
        </div>
    </div>
</div>

{{-- Poster + Info --}}
<div class="container mx-auto px-4 py-8 pb-16">
    <div class="flex flex-col md:flex-row gap-8">

        {{-- Poster --}}
        @if(!empty($tv['poster_path']))
            <div class="flex-none w-36 md:w-52 -mt-4 md:-mt-8 self-start rounded-xl overflow-hidden shadow-2xl ring-1 ring-white/10 z-10">
                <img src="https://image.tmdb.org/t/p/w500/{{ $tv['poster_path'] }}" alt="{{ $tv['name'] }}" class="w-full">
            </div>
        @endif

        {{-- Info --}}
        <div class="flex-1">
            <p class="text-zinc-300 leading-relaxed max-w-2xl">{{ $tv['overview'] }}</p>

            @if(!empty($tv['credits']['crew']))
                <div class="mt-5 flex flex-wrap gap-6">
                    @foreach(collect($tv['credits']['crew'])->take(4) as $crew)
                        <div>
                            <div class="text-white font-semibold text-sm">{{ $crew['name'] }}</div>
                            <div class="text-zinc-500 text-xs">{{ $crew['job'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Seasons --}}
            @if(!empty($tv['seasons']))
                <div class="mt-5">
                    <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">Seasons</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tv['seasons'] as $season)
                            <span class="text-xs px-3 py-1.5 bg-zinc-800 text-zinc-300 rounded-full">
                                {{ $season['name'] }} <span class="text-zinc-500">· {{ $season['episode_count'] }} ep</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @auth
                @if(!is_null($movie_db) && $movie_db['type'] == 1)
                    <div class="mt-4">
                        <livewire:modals.update-episodes :movie_db="$movie_db" />
                    </div>
                @endif
            @endauth

            {{-- Actions --}}
            <div x-data="{ showWatchActions: {{ $movie_db ? 'true' : 'false' }} }"
                 @watchlist-updated.window="showWatchActions = $event.detail"
                 class="mt-8">
                <div class="flex flex-wrap gap-3 items-center">
                    @if(!empty($tv['videos']['results']) && count($tv['videos']['results']) > 0)
                        <button x-data x-on:click="Livewire.dispatchTo('modals.trailer', 'show')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white text-sm font-semibold rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                                <path d="M6.3 2.84A1.5 1.5 0 0 0 4 4.11v11.78a1.5 1.5 0 0 0 2.3 1.27l9.344-5.891a1.5 1.5 0 0 0 0-2.538L6.3 2.84Z" />
                            </svg>
                            Play Trailer
                        </button>
                    @endif

                    @auth
                        <livewire:watchlist :watchItem="$tv" :movie_db="$movie_db" />
                        <div x-show="showWatchActions" x-cloak>
                            <livewire:watch-actions :status="$tv" :movie_db="$movie_db" />
                        </div>
                        <livewire:update-movie-data :updatemovie="$tv" :movie_db="$movie_db" />
                    @endauth
                </div>

                <div class="mt-4 flex items-center gap-3">
                    <span class="text-zinc-500 text-xs font-medium uppercase tracking-wider">Watch on</span>
                    <a href="https://lookmovie2.to/shows/search/?q={{ urlencode($tv['name']) }}" target="_blank"
                        class="text-xs px-3 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 rounded-lg transition-colors">Option 1</a>
                    <a href="https://sflix.to/search/{{ \Str::kebab($tv['name']) }}" target="_blank"
                        class="text-xs px-3 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 rounded-lg transition-colors">Option 2</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Cast --}}
    @if(!empty($tv['credits']['cast']))
        <div class="mt-14">
            <div class="flex items-center mb-5">
                <h2 class="text-base font-semibold text-white uppercase tracking-widest shrink-0">Cast</h2>
                <div class="h-px flex-1 bg-zinc-800 ml-4"></div>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:0.75rem;">
                @foreach(collect($tv['credits']['cast'])->take(16) as $cast)
                    <div class="text-center">
                        <div class="rounded-lg overflow-hidden bg-zinc-800 mb-1.5" style="aspect-ratio:2/3;">
                            @if($cast['profile_path'])
                                <img src="https://image.tmdb.org/t/p/w185/{{ $cast['profile_path'] }}"
                                     alt="{{ $cast['name'] }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-zinc-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-8">
                                        <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <p class="text-white text-xs font-medium leading-tight">{{ $cast['name'] }}</p>
                        <p class="text-zinc-500 text-xs mt-0.5 line-clamp-1">{{ $cast['character'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Backdrops --}}
    @if(!empty($tv['images']['backdrops']))
        <div class="mt-14" x-data="{ open: false, image: '' }">
            <div class="flex items-center mb-5">
                <h2 class="text-base font-semibold text-white uppercase tracking-widest shrink-0">Backdrops</h2>
                <div class="h-px flex-1 bg-zinc-800 ml-4"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach(collect($tv['images']['backdrops'])->take(8) as $image)
                    <a href="#"
                       @click.prevent="open = true; image = 'https://image.tmdb.org/t/p/original/{{ $image['file_path'] }}'"
                       class="block rounded-lg overflow-hidden bg-zinc-800 hover:ring-2 hover:ring-rose-500 transition-all" style="aspect-ratio:16/9;">
                        <img src="https://image.tmdb.org/t/p/w500/{{ $image['file_path'] }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                    </a>
                @endforeach
            </div>
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
                 x-show="open" x-cloak @keydown.escape.window="open = false">
                <button @click="open = false" class="absolute top-4 right-4 text-white/70 hover:text-white text-3xl leading-none">&times;</button>
                <img :src="image" alt="" class="max-w-full max-h-full rounded-lg shadow-2xl">
            </div>
        </div>
    @endif
</div>

<livewire:modals.trailer :movie="$tv" />
@endsection
