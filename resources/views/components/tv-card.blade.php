@php
    if (empty($tv['id'])) return;
    $tvName = $tv['name'] ?? $tv['title'] ?? 'Unknown';
    $searchTitle = urlencode($tvName);
@endphp
<div class="group">
    <a href="{{ route('tv.show', $tv['id']) }}" class="block">
        <div class="relative rounded-lg overflow-hidden bg-zinc-800 aspect-[2/3]">
            @if($tv['poster_path'])
                <img
                    src="https://image.tmdb.org/t/p/w500{{ $tv['poster_path'] }}"
                    alt="{{ $tvName }}"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            @else
                <div class="w-full h-full flex items-center justify-center text-zinc-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-12">
                        <path fill-rule="evenodd" d="M1 4.75C1 3.784 1.784 3 2.75 3h14.5c.966 0 1.75.784 1.75 1.75v10.515a1.75 1.75 0 0 1-1.75 1.75h-1.5c-.078 0-.155-.005-.23-.015H4.48c-.075.01-.152.015-.23.015h-1.5A1.75 1.75 0 0 1 1 15.265V4.75Zm16.5 7.385V11.01a.25.25 0 0 0-.25-.25h-1.5a.25.25 0 0 0-.25.25v1.125c0 .138.112.25.25.25h1.5a.25.25 0 0 0 .25-.25Zm0 2.005a.25.25 0 0 0-.25-.25h-1.5a.25.25 0 0 0-.25.25v1.125c0 .108.069.2.165.235h1.585a.25.25 0 0 0 .25-.25v-1.11Zm-15 1.11v-1.11a.25.25 0 0 1 .25-.25h1.5a.25.25 0 0 1 .25.25v1.125a.25.25 0 0 1-.164.235H2.75a.25.25 0 0 1-.25-.25Zm2-4.24v1.125a.25.25 0 0 1-.25.25h-1.5a.25.25 0 0 1-.25-.25V11.01a.25.25 0 0 1 .25-.25h1.5a.25.25 0 0 1 .25.25Zm13-2.005V7.88a.25.25 0 0 0-.25-.25h-1.5a.25.25 0 0 0-.25.25v1.125c0 .138.112.25.25.25h1.5a.25.25 0 0 0 .25-.25ZM4.25 7.63a.25.25 0 0 1 .25.25v1.125a.25.25 0 0 1-.25.25h-1.5a.25.25 0 0 1-.25-.25V7.88a.25.25 0 0 1 .25-.25h1.5Zm0-3.13a.25.25 0 0 1 .25.25v1.125a.25.25 0 0 1-.25.25h-1.5a.25.25 0 0 1-.25-.25V4.75a.25.25 0 0 1 .25-.25h1.5Zm11.5 1.625a.25.25 0 0 1-.25-.25V4.75a.25.25 0 0 1 .25-.25h1.5a.25.25 0 0 1 .25.25v1.125a.25.25 0 0 1-.25.25h-1.5Zm-9 3.125a.75.75 0 0 0 0 1.5h6.5a.75.75 0 0 0 0-1.5h-6.5Z" clip-rule="evenodd" />
                    </svg>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                <div class="flex gap-2">
                    <a href="https://lookmovie2.to/shows/search/?q={{ $searchTitle }}" target="_blank"
                        class="flex-1 text-center text-xs bg-white/20 backdrop-blur-sm text-white py-1 rounded hover:bg-white/30 transition-colors"
                        onclick="event.stopPropagation()">Watch 1</a>
                    <a href="https://sflix.to/search/{{ \Str::kebab($tvName) }}" target="_blank"
                        class="flex-1 text-center text-xs bg-white/20 backdrop-blur-sm text-white py-1 rounded hover:bg-white/30 transition-colors"
                        onclick="event.stopPropagation()">Watch 2</a>
                </div>
            </div>
            <div class="absolute top-2 right-2">
                <span class="text-xs bg-black/60 backdrop-blur-sm text-yellow-400 px-2 py-0.5 rounded-full font-medium">
                    ★ {{ number_format($tv['vote_average'] ?? 0, 1) }}
                </span>
            </div>
        </div>
        <div class="mt-2 px-0.5">
            <h3 class="text-white text-sm font-medium leading-tight line-clamp-2 group-hover:text-orange-400 transition-colors">
                {{ $tvName }}
            </h3>
            <p class="text-zinc-500 text-xs mt-0.5">
                {{ !empty($tv['first_air_date']) ? \Carbon\Carbon::parse($tv['first_air_date'])->format('Y') : '—' }}
            </p>
        </div>
    </a>
</div>
