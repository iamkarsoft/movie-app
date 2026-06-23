<div>
    <button
        wire:click="toggleWatchlist"
        @class([
            'inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg transition-colors',
            'bg-indigo-600 hover:bg-indigo-700 text-white' => ($isInWatchlist ?? false),
            'bg-zinc-700 hover:bg-zinc-600 text-white' => !($isInWatchlist ?? false),
        ])
    >
        @if($isInWatchlist ?? false)
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
            </svg>
            In Watchlist
        @else
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
            </svg>
            Add to Watchlist
        @endif
    </button>
</div>
