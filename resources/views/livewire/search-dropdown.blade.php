<div class="w-full relative mt-3 md:mt-0" x-data="{ isOpen: true }" @click.away="isOpen = false">
    <input wire:model.live.debounce.500ms="search" type="text"
        class="bg-gray-800 px-5 w-65 pl-8 py-1 w-full rounded-full focus:outline-none focus:shadow-outline"
        placeholder="search" x-ref="search"
        x-on:keydown="(e) => {
            if(e.keyCode === 191) {
                e.preventDefault();
                $refs.search.focus();
            }
        }"
        @focus="isOpen = true" @keydown.escape.window="isOpen = false" @keydown="isOpen=true"
        @keydown.shift.tab="isOpen = false">

    <div wire:loading class="spinner top-0 right-0 mr-4 mt-4"></div>

    @if (strlen($search) > 2)
        <div class="absolute bg-gray-800 text-sm rounded w-full lg:w-72 mt-4 z-50 shadow-xl" x-show="isOpen" x-cloak>
            @if ($searchResults->count() > 0 || $searchTvResults->count() > 0)

                @if ($searchResults->count() > 0)
                    <div class="px-4 pt-3 pb-1 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-white">Movies</h3>
                    </div>
                    <ul>
                        @foreach ($searchResults as $result)
                            <li>
                                <a href="{{ route('movie.show', $result['id']) }}"
                                    class="flex items-center gap-3 px-4 py-2 hover:bg-gray-700 transition-colors">
                                    <img src="{{ $result['poster_path'] ? 'https://image.tmdb.org/t/p/w92/' . $result['poster_path'] : asset('img/blank_movie_poster.jfif') }}"
                                        class="w-8 h-12 object-cover rounded shrink-0" alt="">
                                    <span class="text-sm text-white leading-tight">{{ $result['title'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @if($movieTotal > 3)
                    <div class="px-4 py-2 border-t border-gray-700">
                        <a href="{{ route('search.movies', ['q' => $search]) }}"
                            class="text-xs text-orange-400 hover:text-orange-300 flex items-center gap-1">
                            More movie results
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-3">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    @endif
                @endif

                @if ($searchTvResults->count() > 0)
                    <div class="px-4 pt-3 pb-1 flex items-center justify-between {{ $searchResults->count() > 0 ? 'border-t border-gray-700' : '' }}">
                        <h3 class="text-base font-semibold text-white">Series</h3>
                    </div>
                    <ul>
                        @foreach ($searchTvResults as $result)
                            <li>
                                <a href="{{ route('tv.show', $result['id']) }}"
                                    class="flex items-center gap-3 px-4 py-2 hover:bg-gray-700 transition-colors">
                                    <img src="{{ $result['poster_path'] ? 'https://image.tmdb.org/t/p/w92/' . $result['poster_path'] : asset('img/blank_movie_poster.jfif') }}"
                                        class="w-8 h-12 object-cover rounded shrink-0" alt="">
                                    <span class="text-sm text-white leading-tight">{{ $result['name'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @if($tvTotal > 3)
                    <div class="px-4 py-2 border-t border-gray-700">
                        <a href="{{ route('search.tv', ['q' => $search]) }}"
                            class="text-xs text-orange-400 hover:text-orange-300 flex items-center gap-1">
                            More series results
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-3">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    @endif
                @endif

            @else
                <div class="py-3 px-4 text-gray-400">No results for "{{ $search }}"</div>
            @endif
        </div>
    @endif
</div>
