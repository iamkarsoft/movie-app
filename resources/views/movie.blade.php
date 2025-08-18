@extends('layout.app')
@section('content')
    @php
        $searchTitle = urlencode($movie['title']);
    @endphp
    <div class="border-b border-gray-800 movie-info">
        <div class="container flex flex-col px-4 py-16 mx-auto md:flex-row">
            <div class="flex-none">
                <img src="{{ 'https://image.tmdb.org/t/p/w500/' . $movie['poster_path'] }}" alt=""
                    class="w-full md:w-64 lg:w-94">
            </div>

            <div class="px-8 md:ml-24">
                <h2 class="mt-2 text-4xl transition ease-in-out hover:opacity-75 hover:text-gray-300">{{ $movie['title'] }}
                </h2>

                <div class="mt-2">
                    <div class="flex items-center mt-1 text-gray-400">
                        <span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="w-6 h-6 text-red-600">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg></span>
                        <span class="ml-1">{{ $movie['vote_average'] * 10 . '%' }}</span>
                        <span class="mx-2">|</span>
                        <span>{{ \Carbon\Carbon::parse($movie['release_date'])->format('M d, Y') }}</span>
                    </div>
                    <div class="text-sm text-gray-400">
                        @foreach ($movie['genres'] as $genre)
                            {{ $genre['name'] }}
                        @endforeach
                    </div>
                </div>
                <p class="mt-8 text-gray-300">
                    {{ $movie['overview'] }}
                </p>

                <div class="mt-12">
                    <div class="flex gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd"
                                d="M1 4.75C1 3.784 1.784 3 2.75 3h14.5c.966 0 1.75.784 1.75 1.75v10.515a1.75 1.75 0 0 1-1.75 1.75h-1.5c-.078 0-.155-.005-.23-.015H4.48c-.075.01-.152.015-.23.015h-1.5A1.75 1.75 0 0 1 1 15.265V4.75Zm16.5 7.385V11.01a.25.25 0 0 0-.25-.25h-1.5a.25.25 0 0 0-.25.25v1.125c0 .138.112.25.25.25h1.5a.25.25 0 0 0 .25-.25Zm0 2.005a.25.25 0 0 0-.25-.25h-1.5a.25.25 0 0 0-.25.25v1.125c0 .108.069.2.165.235h1.585a.25.25 0 0 0 .25-.25v-1.11Zm-15 1.11v-1.11a.25.25 0 0 1 .25-.25h1.5a.25.25 0 0 1 .25.25v1.125a.25.25 0 0 1-.164.235H2.75a.25.25 0 0 1-.25-.25Zm2-4.24v1.125a.25.25 0 0 1-.25.25h-1.5a.25.25 0 0 1-.25-.25V11.01a.25.25 0 0 1 .25-.25h1.5a.25.25 0 0 1 .25.25Zm13-2.005V7.88a.25.25 0 0 0-.25-.25h-1.5a.25.25 0 0 0-.25.25v1.125c0 .138.112.25.25.25h1.5a.25.25 0 0 0 .25-.25ZM4.25 7.63a.25.25 0 0 1 .25.25v1.125a.25.25 0 0 1-.25.25h-1.5a.25.25 0 0 1-.25-.25V7.88a.25.25 0 0 1 .25-.25h1.5Zm0-3.13a.25.25 0 0 1 .25.25v1.125a.25.25 0 0 1-.25.25h-1.5a.25.25 0 0 1-.25-.25V4.75a.25.25 0 0 1 .25-.25h1.5Zm11.5 1.625a.25.25 0 0 1-.25-.25V4.75a.25.25 0 0 1 .25-.25h1.5a.25.25 0 0 1 .25.25v1.125a.25.25 0 0 1-.25.25h-1.5Zm-9 3.125a.75.75 0 0 0 0 1.5h6.5a.75.75 0 0 0 0-1.5h-6.5Z"
                                clip-rule="evenodd" />
                        </svg>
                        <h4 class="font-semibold text-white">Cast</h4>
                    </div>
                    <div class="flex mt-4">
                        @foreach ($movie['credits']['crew'] as $crew)
                            @if ($loop->index < 4)
                                <div class="mx-4">
                                    <div class="text-lg font-bold">{{ $crew['name'] }}</div>
                                    <div>{{ $crew['job'] }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div x-data="{ isOpen: false }">
                    <div class="flex gap-2 mt-12">
                        @if (count($movie['videos']['results']) > 0)
                            <button @click=" isOpen = true"
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


                        <livewire:update-movie-data :updatemovie="$movie" />

                        @auth
                            @if (is_array($movie) && !empty($movie))
                                <livewire:watchlist :watchItem="$movie" :movie_db="$movie_db" />
                                @if ($movie_db)
                                    <livewire:watch-actions :status="$movie" :movie_db="$movie_db" />
                                @endif
                            @endif
                        @endauth

                    </div>

                    <div class="mt-4 text-gray-400 text-sm flex gap-2 my-2">
                        <a href="https://lookmovie2.to/movies/search/?q={{ $searchTitle }}" target="_blank"
                            class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 text-red-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                            <span>1</span>
                        </a> &nbsp;

                        <a href="https://sflix.to/search/{{ \Str::kebab($movie['title']) }}" target="_blank"
                            class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 text-red-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                            <span>2</span>
                        </a>
                    </div>

                    <!-- modal -->
                    <div style="background-color: rgba(0,0,0,0.5);"
                        class="fixed top-0 left-0 flex items-center w-full h-full overflow-y-auto shadow-lg"
                        x-show="isOpen">
                        <div class="container mx-auto overflow-y-hidden rounded-lg lg:px-32">
                            <div class="bg-gray-900 rounded">
                                <div class="flex justify-end pt-2 pr-4">
                                    <button class="text-3xl leading-none hover:text-gray-300 close-modal"
                                        @click="isOpen=false;stopVideos()">&times;
                                    </button>
                                </div>
                                <div class="px-8 py-8 modal-body">
                                    <div class="relative overflow-hidden responsive-container" style="padding-top: 56.25%">
                                        <iframe id="ytfullplayer"
                                            src="https://youtube.com/embed/{{ $movie['videos']['results'][0]['key'] }}"
                                            width="560" height="315"
                                            class="absolute top-0 left-0 w-full h-full responsive-iframe" frameborder="0"
                                            allow="autoplay; encrypted-media" style="border:0"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- modal end -->
                </div>
            </div>
        </div>

        <div class="border-b border-gray-800 movie-cast">
            <div class="container px-4 py-16 mx-auto">
                <h2 class="text-4xl font-semibold">Cast</h2>
            </div>
            <div class="grid-cols-1 gap-8 px-4 md:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 x ">
                @foreach ($movie['credits']['cast'] as $cast)
                    @if ($loop->index < 8)
                        <div class="mt-8">

                            <img src="{{ 'https://image.tmdb.org/t/p/w500/' . $cast['profile_path'] }}" alt="">
                            <div class=" my-2">
                                <div class="text-lg font-bold flex gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path
                                            d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                                    </svg>
                                    <span> {{ $cast['name'] }}</span>
                                </div>
                                <div class="flex gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path
                                            d="M3.25 4A2.25 2.25 0 0 0 1 6.25v7.5A2.25 2.25 0 0 0 3.25 16h7.5A2.25 2.25 0 0 0 13 13.75v-7.5A2.25 2.25 0 0 0 10.75 4h-7.5ZM19 4.75a.75.75 0 0 0-1.28-.53l-3 3a.75.75 0 0 0-.22.53v4.5c0 .199.079.39.22.53l3 3a.75.75 0 0 0 1.28-.53V4.75Z" />
                                    </svg>

                                    <span>{{ $cast['character'] }}</span>
                                </div>
                            </div>

                        </div>
                    @endif
                @endforeach
            </div>
        </div><!-- /cast -->


        <div class="border-b border-gray-800 movie-cast" x-data="{ isOpen: false, image: '' }">
            <div class="container px-4 py-16 mx-auto">
                <h2 class="text-4xl font-semibold">Back Drops</h2>
            </div>
            <div class="grid grid-cols-1 gap-8 px-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 x ">
                @foreach ($movie['images']['backdrops'] as $images)
                    @if ($loop->index < 8)
                        <div class="mt-8">

                            <a @click.prevent="isOpen=true,  image='{{ 'https://image.tmdb.org/t/p/original/' . $images['file_path'] }}'"
                                href="#"> <img src="{{ 'https://image.tmdb.org/t/p/w500/' . $images['file_path'] }}"
                                    alt=""></a>
                        </div>
                    @endif
                @endforeach

            </div><!-- /images -->
        </div><!-- end  movie info-->

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
@endsection
