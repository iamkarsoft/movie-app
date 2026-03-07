<div>
    @if (count($movie['videos']['results']) > 0)
        <button wire:click="show"
            class="inline-flex items-center px-4 py-4 font-semibold text-gray-900 transition ease-in-out bg-purple-500 rounded hover:bg-purple-600">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                </path>
            </svg>
            <span class="ml-2">Play Trailer</span>
        </button>
    @endif

    @if ($visible)
        <div class="fixed inset-0 z-50" style="background-color: rgba(0,0,0,0.5);">
            <div class="fixed top-0 left-0 flex items-center w-full h-full overflow-y-auto shadow-lg">
                <div class="container mx-auto overflow-y-hidden rounded-lg lg:px-32">
                    <div class="bg-gray-900 rounded">
                        <div class="flex justify-end pt-2 pr-4">
                            <button wire:click="hide" class="text-3xl leading-none hover:text-gray-300">&times;</button>
                        </div>
                        <div class="px-8 py-8">
                            <div class="relative overflow-hidden" style="padding-top: 56.25%">
                                <iframe id="ytfullplayer"
                                    src="https://youtube.com/embed/{{ $movie['videos']['results'][0]['key'] }}"
                                    width="560" height="315"
                                    class="absolute top-0 left-0 w-full h-full" frameborder="0"
                                    allow="autoplay; encrypted-media" style="border:0"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
