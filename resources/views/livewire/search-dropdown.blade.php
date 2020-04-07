<div class="relative mt-3 md:mt-8">
    <input wire:model.debounce.500ms="search" type="text" class="bg-gray-800 px-5 w-65 pl-8 py-1 rounded-full focus:outline-none focus:shadow-outline" placeholder="search">

  <div wire:loading class="spinner top-0 right-0 mr-4 mt-4"></div>
@if(strlen($search)>2)
            <div class="absolute bg-gray-800 text-sm rounded w-64 mt-4">
              @if($searchResults->count() >0)
                <ul>
                    <li class="border-b border-gray-700 ">
                      @foreach($searchResults as $result)
                        <a href="{{route('movie.show',$result['id'])}}" class="block hover:bg-gray-700 py-3 flex">
                  @if($result['poster_path'])
                          <img src="https://image.tmdb.org/t/p/w92/{{$result['poster_path']}}" class="w-8" alt="">
                          <span>{{$result['title']}}</span></a>
                  @else

                  @endif
                      @endforeach


                    </li>
                </ul>

                @else
                <div class="py-3 px-3">No Results for {{$search}}</div>
                @endif
            </div>
  @endif
</div>
