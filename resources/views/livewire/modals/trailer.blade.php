<x-modal-wrapper wire:model="visible">
    <div
        class="relative overflow-hidden responsive-container" style="padding-top: 56.25%">
        <iframe id="ytfullplayer"
            src="https://youtube.com/embed/{{ $movie['videos']['results'][0]['key'] }}"
            width="560" height="315"
            class="absolute top-0 left-0 w-full h-full responsive-iframe" frameborder="0"
            allow="autoplay; encrypted-media" style="border:0"></iframe>
    </div>
</x-modal-wrapper>

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
