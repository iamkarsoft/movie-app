<x-modal-wrapper wire:model="visible">
    <div class="relative overflow-hidden" style="padding-top: 56.25%">
        @if($videoKey)
            <div wire:ignore class="absolute top-0 left-0 w-full h-full">
                <iframe
                    x-data="{ key: '{{ $videoKey }}' }"
                    x-bind:src="$wire.visible ? 'https://www.youtube.com/embed/' + key + '?autoplay=1' : ''"
                    class="w-full h-full"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>
        @endif
    </div>
</x-modal-wrapper>
