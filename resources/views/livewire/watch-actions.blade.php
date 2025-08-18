<div class="mx-4">
    <section class="text-white font-extrabold">
        @if (session()->has('message'))
            <span>{{ session('message') }}</span>
        @endif
    </section>
    <div class="w-40 flex-column border-slate-200 relative bg-purple-500 text-gray-900 rounded font-semibold px-4 py-4 transition ease-in-out hover:bg-purple-600"
        x-data="{ filter: false }">
        <button x-on:click="filter=true" class="relative flex ">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" viewBox="0 0 20 20" fill="currentColor">
                <path
                    d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" />
            </svg><span>Watch Status</span></button>
        <ul x-show="filter" x-on:click.away="filter=false"
            class="border-t-2 bg-gray-100 z-50 border-slate-200 absolute w-40 lg:mt-2 lg:left-0">
            {{-- @if ($movie_db['watch_type'] == 0) --}}
            <li wire:click="$dispatch('status',[0])" class="inline-flex items-center w-full cursor-pointer">
                @if ($movie_db['watch_type'] == 0)
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                @else
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                @endif
                <span class="ml-2">Watching</span>
            </li>
            <li wire:click="$dispatch('status',[1])" class="inline-flex items-center w-full cursor-pointer">
                @if ($movie_db['watch_type'] == 1)
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                @else
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                @endif
                <span class="ml-2">Watched</span>
            </li>
            {{-- @else --}}
            <li wire:click="$dispatch('status',[2])" class="inline-flex items-center w-full cursor-pointer">
                @if ($movie_db['watch_type'] == 2)
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                @else
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                @endif
                <span class="ml-2">Abandoned</span>
            </li>
            {{-- @endif --}}
        </ul>

    </div>

</div>
