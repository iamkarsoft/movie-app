<div
    class="fixed inset-0 z-50"
    x-data="{ visible: @entangle($attributes->wire('model')) }"
    x-show="visible"
    x-cloak
>
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
        x-show="visible"
        x-transition.opacity.duration.300ms
        x-on:click="Livewire.dispatchTo('{{ $this->getName() }}', 'hide'); stopVideos()"
    ></div>

    <div class="fixed top-0 left-0 flex items-center w-full h-full overflow-y-auto shadow-lg">
        <div class="container mx-auto overflow-y-hidden rounded-lg lg:px-32">
            <div class="bg-gray-900 rounded">
                <div class="flex justify-end pt-2 pr-4">
                    <button class="text-3xl leading-none hover:text-gray-300"
                        x-on:click="Livewire.dispatchTo('{{ $this->getName() }}', 'hide');stopVideos()">&times;
                    </button>
                </div>
                <div class="px-8 py-8 modal-body">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>

 <script>
        let playVideoButton = document.querySelector('.ytp-play-button');
        let player = document.querySelector('#ytfullplayer');
        let closeModal = document.querySelector('.close-modal');


        let stopVideos = function() {
            let videos = document.querySelectorAll('iframe, video');
            Array.prototype.forEach.call(videos, function(video) {
                if (video.tagName.toLowerCase() === 'video') {
                    video.pause();
                } else {
                    let src = video.src;
                    video.src = src;
                }
            });
        };
    </script>
