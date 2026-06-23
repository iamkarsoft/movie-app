@php
    if (empty($movie['id'])) return;
    $movieTitle = $movie['title'] ?? $movie['name'] ?? 'Unknown';
    $searchTitle = urlencode($movieTitle);
@endphp
<div class="group">
    <a href="{{ route('movie.show', $movie['id']) }}" class="block">
        <div class="relative rounded-lg overflow-hidden bg-zinc-800 aspect-[2/3]">
            @if($movie['poster_path'])
                <img
                    src="https://image.tmdb.org/t/p/w500/{{ $movie['poster_path'] }}"
                    alt="{{ $movieTitle }}"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            @else
                <div class="w-full h-full flex items-center justify-center text-zinc-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-12">
                        <path d="M3.25 4A2.25 2.25 0 0 0 1 6.25v7.5A2.25 2.25 0 0 0 3.25 16h7.5A2.25 2.25 0 0 0 13 13.75v-7.5A2.25 2.25 0 0 0 10.75 4h-7.5ZM19 4.75a.75.75 0 0 0-1.28-.53l-3 3a.75.75 0 0 0-.22.53v4.5c0 .199.079.39.22.53l3 3a.75.75 0 0 0 1.28-.53V4.75Z" />
                    </svg>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                <div class="flex gap-2">
                    <a href="https://lookmovie2.to/movies/search/?q={{ $searchTitle }}" target="_blank"
                        class="flex-1 text-center text-xs bg-white/20 backdrop-blur-sm text-white py-1 rounded hover:bg-white/30 transition-colors"
                        onclick="event.stopPropagation()">Watch 1</a>
                    <a href="https://sflix.to/search/{{ \Str::kebab($movieTitle) }}" target="_blank"
                        class="flex-1 text-center text-xs bg-white/20 backdrop-blur-sm text-white py-1 rounded hover:bg-white/30 transition-colors"
                        onclick="event.stopPropagation()">Watch 2</a>
                </div>
            </div>
            <div class="absolute top-2 right-2">
                <span class="text-xs bg-black/60 backdrop-blur-sm text-yellow-400 px-2 py-0.5 rounded-full font-medium">
                    ★ {{ number_format($movie['vote_average'] ?? 0, 1) }}
                </span>
            </div>
        </div>
        <div class="mt-2 px-0.5">
            <h3 class="text-white text-sm font-medium leading-tight line-clamp-2 group-hover:text-orange-400 transition-colors">
                {{ $movieTitle }}
            </h3>
            <p class="text-zinc-500 text-xs mt-0.5">
                {{ !empty($movie['release_date']) ? \Carbon\Carbon::parse($movie['release_date'])->format('Y') : '—' }}
            </p>
        </div>
    </a>
</div>
