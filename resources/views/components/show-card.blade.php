 <div class="mt-8">

    <a href="{{route('tv.show',$tv['id'])}}">
      <img src="{{'https://image.tmdb.org/t/p/w500/'.$tv['poster_path']}}" alt="">

       <div class="mt-2">
         <a href="" class=" text-xl my-2 mt-2  hover:opacity-75 transition ease-in-out  hover:text-gray-300">{{$tv['name']}}</a>
             <div class="flex items-center text-gray-400 mt-1">
               <span><svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6"><path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg></span>
               {{-- <span class="ml-1">{{ $movie['vote_average'] * 10 . '%'}}</span> --}}
               <span class="mx-2">|</span>
               <span>{{ \Carbon\Carbon::parse($tv['first_air_date'])->format('M d, Y')}}</span>
             </div>
             <div class="text-gray-400 text-sm">
              {{-- {{$movie['genres']}}@if(!$loop->last) , @endif --}}
             </div>
         </div>
      </a>
 </div>
