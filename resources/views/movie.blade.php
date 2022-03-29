@extends('layout.app')
@section('content')
  <div class="movie-info border-b border-gray-800">
    <div class="container mx-auto px-4 py-16 flex flex-col  md:flex-row">
     <div class="flex-none">
         <img src="{{'https://image.tmdb.org/t/p/w500/'.$movie['poster_path']}}" alt="" class="w-full md:w-64 lg:w-94">
     </div>

      <div class="md:ml-24 px-8">
          <h2 class=" text-4xl mt-2  hover:opacity-75 transition ease-in-out  hover:text-gray-300">{{$movie['title']}}</h2>

              <div class="mt-2">
             <div class="flex items-center text-gray-400 mt-1">
               <span>   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-red-600">
  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
</svg></span>
               <span class="ml-1">{{ $movie['vote_average'] * 10 . '%'}}</span>
               <span class="mx-2">|</span>
               <span>{{ \Carbon\Carbon::parse($movie['release_date'])->format('M d, Y')}}</span>
             </div>
             <div class="text-gray-400 text-sm">
             @foreach($movie['genres'] as $genre)
              {{$genre['name']}}
             @endforeach
             </div>
         </div>
            <p class="text-gray-300 mt-8">
             {{ $movie['overview']}}
            </p>

            <div class="mt-12">
              <h4 class="text-white font-semibold">Cast</h4>
              <div class="flex mt-4">
                @foreach($movie['credits']['crew'] as $crew)
                  @if($loop->index <4)
                <div class="mx-4">
                  <div class="font-bold text-lg">{{$crew['name']}}</div>
                  <div>{{$crew['job']}}</div>
                </div>
                @endif
               @endforeach
              </div>
            </div>
<div x-data="{isOpen : false }">
              @if(count($movie['videos']['results']) > 0)
              <div class="mt-12 flex ">
                <button @click=" isOpen = true" class=" mx-4 inline-flex items-center bg-purple-500 text-gray-900 rounded font-semibold px-4 py-4 transition ease-in-out hover:bg-purple-600">
                  <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                  <span class="ml-2">Play Trailer</span>
                </button>

                  @auth
                  <livewire:watchlist  :watchItem="$movie"/>
                  @endauth
              </div>

                <!-- modal -->
    <div
      style="background-color: rgba(0,0,0,0.5);"
      class="fixed top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto"
      x-show="isOpen"
    >
      <div class="container mx-auto lg:px-32 rounded-lg overflow-y-hidden">
        <div class="bg-gray-900 rounded">
          <div class="flex justify-end pr-4 pt-2">
            <button class="text-3xl leading-none hover:text-gray-300" @click="isOpen=false">&times;</button>
          </div>
          <div class="modal-body px-8 py-8">
            <div class="responsive-container overflow-hidden relative" style="padding-top: 56.25%">
              <iframe id="ytfullplayer" src="https://youtube.com/embed/{{ $movie['videos']['results'][0]['key']}}" width="560" height="315" class="responsive-iframe absolute top-0 left-0 w-full h-full" frameborder="0" allow="autoplay; encrypted-media" style="border:0"></iframe>
            </div>
          </div>
        </div>

      </div>

    </div> <!-- modal end -->
              @endif
  </div>

      </div>
    </div>

<div class="movie-cast border-b border-gray-800">
  <div class="container mx-auto px-4 py-16">
    <h2 class="text-4xl font-semibold">Cast</h2>
  </div>
        <div class="md:grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 x gap-8 px-4 ">
           @foreach($movie['credits']['cast'] as $cast)
           @if($loop->index < 8)
      <div class="mt-8">

      <img src="{{'https://image.tmdb.org/t/p/w500/'.$cast['profile_path']}}" alt="">
      <div class="mx-4">
                  <div class="font-bold text-lg">{{$cast['name']}}</div>
                  <div>{{$cast['character']}}</div>
                </div>

       </div>
       @endif
   @endforeach
        </div>
</div><!-- /cast -->



<div class="movie-cast border-b border-gray-800"  x-data="{isOpen:false,image:''}">
  <div class="container mx-auto px-4 py-16">
    <h2 class="text-4xl font-semibold">Back Drops</h2>
  </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 x gap-8 px-4 ">
   @foreach($movie['images']['backdrops'] as $images)
   @if($loop->index < 8)
      <div class="mt-8">

     <a
      @click.prevent="isOpen=true,  image='{{'https://image.tmdb.org/t/p/original/'.$images['file_path']}}'"

     href="#"> <img src="{{'https://image.tmdb.org/t/p/w500/'.$images['file_path']}}" alt=""></a>
        </div>
       @endif
   @endforeach

</div><!-- /images -->
              <!-- modal -->
    <div
      style="background-color: rgba(0,0,0,0.5);"
      class="fixed top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto"
      x-show="isOpen"
    >
      <div class="container mx-auto lg:px-32 rounded-lg overflow-y-hidden">
        <div class="bg-gray-900 rounded">
          <div class="flex justify-end pr-4 pt-2">
            <button class="text-3xl leading-none hover:text-gray-300 close-modal"
            @keydown.escape.window="isOpen=false"
            @click="isOpen=false,player.stopVideo():Void"  >&times;</button>
          </div>
          <div class="modal-body px-8 py-8">

             <img :src="image" alt="poster">

          </div>
        </div>

      </div>

    </div> <!-- modal end -->
  </div><!-- end  movie info-->

</div>

<script>

  let playVideoButton = document.querySelector('.ytp-play-button');
  let closeModal = document.querySelector('.close-modal');



  closeModal.addEventListener('click', function(){
        console.log('stopped');
  })


</script>

@endsection
