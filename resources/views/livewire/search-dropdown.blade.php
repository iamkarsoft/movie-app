<div class="w-full relative mt-3 md:mt-0" x-data="{isOpen : true}" @click.away="isOpen = false">
    <input wire:model.live.debounce.500ms="search" type="text" class="bg-gray-800 px-5 w-65 pl-8 py-1 w-full  rounded-full focus:outline-none focus:shadow-outline" placeholder="search"

    x-ref="search"
     x-on:keydown="(e) => {
            if(e.keyCode === 191) {
                e.preventDefault();
                $refs.search.focus();
            }
        }"
    @focus="isOpen = true"
    @keydown.escape.window="isOpen = false"
    @keydown="isOpen=true"
    @keydown.shift.tab="isOpen = false"
    >

  <div wire:loading class="spinner top-0 right-0 mr-4 mt-4"></div>
@if(strlen($search)>2)
            <div  class="absolute bg-gray-800 text-sm rounded w-full lg:w-64 mt-4" x-show="isOpen"
            >
            @if($searchResults->count()>0 || $searchTvResults->count()>0)
              @if($searchResults->count() >0)
                <h3 class="text-xl text-bold mx-4 my-2">Movies</h3>
                <ul>
                    <li class="border-b border-gray-700  px-4">
                      @foreach($searchResults as $result)
                        <a
                          @if($loop->last) @keydown.tab="isOpen = false" @endif
                        href="{{route('movie.show',$result['id'])}}" class="block hover:bg-gray-700 py-3 flex">
                  @if($result['poster_path'])
                          <img src="https://image.tmdb.org/t/p/w92/{{$result['poster_path']}}" class="w-8" alt="">
                          <span class="text-lg px-2">{{$result['title']}}</span></a>
                  @else
                     <img src="{{asset('img/blank_movie_poster.jfif')}}" class="w-8" alt="">
                          <span class="text-lg px-2">{{$result['title']}}</span></a>
                  @endif
                      @endforeach


                    </li>
                </ul>
                @endif
                @if($searchTvResults->count() >0)
                <h3 class="text-xl text-bold mx-4 my-2">Series</h3>
                 <ul>
                    <li class="border-b border-gray-700  px-4">
                      @foreach($searchTvResults as $result)
                        <a
                          @if($loop->last) @keydown.tab="isOpen = false" @endif
                        href="{{route('tv.show',$result['id'])}}" class="block hover:bg-gray-700 py-3 flex">
                  @if($result['poster_path'])
                          <img src="https://image.tmdb.org/t/p/w92/{{$result['poster_path']}}" class="w-8" alt="">
                          <span class="text-lg px-2">{{$result['name']}}</span></a>
                  @else
                    <img src="{{asset('img/blank_movie_poster.jfif')}}" class="w-8" alt="">
                          <span class="text-lg px-2">{{$result['name']}}</span></a>

                  @endif
                      @endforeach


                    </li>
                </ul>
                @endif
                @else
                <div class="py-3 px-3">No Results for {{$search}}</div>
                @endif
            </div>
  @endif
</div>
