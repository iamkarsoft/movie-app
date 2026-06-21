<div>
    @auth
        @if(isset($updatemovie['first_air_date']) && $inWatchlist)
        <button wire:click="show"
            class="inline-flex items-center px-4 py-4 font-semibold text-gray-900 transition ease-in-out bg-yellow-500 rounded hover:bg-yellow-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z" clip-rule="evenodd" />
            </svg>
            <span class="ml-2">Sync Data</span>
        </button>

        <x-modal-wrapper wire:model="visible">
            <div class="min-w-[20rem]">
                <h3 class="text-lg font-semibold text-white mb-3">Sync TMDB Data</h3>
                <p class="text-gray-400 text-sm">
                    Links
                    <span class="font-semibold text-white">{{ $updatemovie['title'] ?? $updatemovie['name'] ?? '' }}</span>
                    to TMDB ID <span class="font-semibold text-white">{{ $updatemovie['id'] }}</span>
                    and updates dates in your watchlist.
                </p>
                <div class="mt-6 flex gap-2">
                    <button wire:click="syncData"
                        class="rounded bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Sync
                    </button>
                    <button wire:click="hide"
                        class="rounded bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                        Cancel
                    </button>
                </div>
            </div>
        </x-modal-wrapper>
        @endif
    @endauth
</div>
