@extends('layout.app')
@section('content')
    @php
       $searchTitle = urlencode(\Str::slug($movie['title'], '%20'));
    @endphp
    <div class="border-b border-gray-800 movie-info">
        <div class="container flex flex-col px-4 py-16 mx-auto md:flex-row">
            <div class="flex-none">
                <img src="{{'https://image.tmdb.org/t/p/w500/'.$movie['poster_path']}}" alt=""
                     class="w-full md:w-64 lg:w-94">
            </div>

            <div class="px-8 md:ml-24">
                <h2 class="mt-2 text-4xl transition ease-in-out hover:opacity-75 hover:text-gray-300">{{$movie['title']}}</h2>

                <div class="mt-2">
                    <div class="flex items-center mt-1 text-gray-400">
               <span>   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             fill="currentColor" class="w-6 h-6 text-red-600">
  <path
      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
</svg></span>
                        <span class="ml-1">{{ $movie['vote_average'] * 10 . '%'}}</span>
                        <span class="mx-2">|</span>
                        <span>{{ \Carbon\Carbon::parse($movie['release_date'])->format('M d, Y')}}</span>
                    </div>
                    <div class="text-sm text-gray-400">
                        @foreach($movie['genres'] as $genre)
                            {{$genre['name']}}
                        @endforeach
                    </div>
                </div>
                <p class="mt-8 text-gray-300">
                    {{ $movie['overview']}}
                </p>

                <div class="mt-12">
                    <h4 class="font-semibold text-white">Cast</h4>
                    <div class="flex mt-4">
                        @foreach($movie['credits']['crew'] as $crew)
                            @if($loop->index <4)
                                <div class="mx-4">
                                    <div class="text-lg font-bold">{{$crew['name']}}</div>
                                    <div>{{$crew['job']}}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div x-data="{isOpen : false }">
                    <div class="flex mt-12 ">

                        @if(count($movie['videos']['results']) > 0)
                            <button @click=" isOpen = true"
                                    class="inline-flex items-center px-4 py-4 mx-4 font-semibold text-gray-900 transition ease-in-out bg-purple-500 rounded hover:bg-purple-600">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                     stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                     class="w-6 h-6">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span class="ml-2">Play Trailer</span>
                            </button>
                        @endif


                        <livewire:update-movie-data :updatemovie="$movie"/>

                        @auth
                            <livewire:watchlist :watchItem="$movie" :movie_db="$movie_db" />

                            @if($movie_db)
                                <livewire:watch-actions :status="$movie" :movie_db="$movie_db" />
                            @endif
                        @endauth

                    </div>

        <div class="mt-4 text-gray-400 text-sm flex gap-2 my-2">
            <a href="https://lookmovie2.to/movies/search/?q={{$searchTitle}}" target="_blank" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Viewing Option 1</span>
            </a> &nbsp;

            <a href="https://sflix.to/search/{{\Str::kebab($movie['title'])}}" target="_blank" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Viewing Option 2</span>
            </a>
        </div>


                    <!-- modal -->
                    <div
                        style="background-color: rgba(0,0,0,0.5);"
                        class="fixed top-0 left-0 flex items-center w-full h-full overflow-y-auto shadow-lg"
                        x-show="isOpen"
                    >
                        <div class="container mx-auto overflow-y-hidden rounded-lg lg:px-32">
                            <div class="bg-gray-900 rounded">
                                <div class="flex justify-end pt-2 pr-4">
                                    <button
                                        class="text-3xl leading-none hover:text-gray-300 close-modal"
                                        @click="isOpen=false;stopVideos()">&times;
                                    </button>
                                </div>
                                <div class="px-8 py-8 modal-body">
                                    <div class="relative overflow-hidden responsive-container"
                                         style="padding-top: 56.25%">
                                        <iframe id="ytfullplayer"
                                                src="https://youtube.com/embed/{{ $movie['videos']['results'][0]['key']}}"
                                                width="560" height="315"
                                                class="absolute top-0 left-0 w-full h-full responsive-iframe"
                                                frameborder="0" allow="autoplay; encrypted-media"
                                                style="border:0"></iframe>
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
            <div
                class="grid-cols-1 gap-8 px-4 md:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 x ">
                @foreach($movie['credits']['cast'] as $cast)
                    @if($loop->index < 8)
                        <div class="mt-8">

                            <img src="{{'https://image.tmdb.org/t/p/w500/'.$cast['profile_path']}}"
                                 alt="">
                            <div class="mx-4">
                                <div class="text-lg font-bold">{{$cast['name']}}</div>
                                <div>{{$cast['character']}}</div>
                            </div>

                        </div>
                    @endif
                @endforeach
            </div>
        </div><!-- /cast -->


        <div class="border-b border-gray-800 movie-cast" x-data="{isOpen:false,image:''}">
            <div class="container px-4 py-16 mx-auto">
                <h2 class="text-4xl font-semibold">Back Drops</h2>
            </div>
            <div
                class="grid grid-cols-1 gap-8 px-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 x ">
                @foreach($movie['images']['backdrops'] as $images)
                    @if($loop->index < 8)
                        <div class="mt-8">

                            <a
                                @click.prevent="isOpen=true,  image='{{'https://image.tmdb.org/t/p/original/'.$images['file_path']}}'"

                                href="#"> <img
                                    src="{{'https://image.tmdb.org/t/p/w500/'.$images['file_path']}}"
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


        let stopVideos = function () {
            let videos = document.querySelectorAll('iframe, video');
            Array.prototype.forEach.call(videos, function (video) {
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
