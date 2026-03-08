<x-modal-wrapper wire:model="visible">
    <div class="relative overflow-hidden" style="padding-top: 56.25%">
        @if(!empty($movie['videos']['results']))
             <iframe id="ytfullplayer"
            src="https://youtube.com/embed/{{ $movie['videos']['results'][0]['key'] }}"
            width="560" height="315"
            class="absolute top-0 left-0 w-full h-full" frameborder="0"
            allow="autoplay; encrypted-media" style="border:0"></iframe>
        @endif
    </div>
</x-modal-wrapper>
