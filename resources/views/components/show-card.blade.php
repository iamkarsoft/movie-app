
<div class="mt-8">
    <a href="{{route('tv.show',$tv['id'])}}">
      <img src="{{'https://image.tmdb.org/t/p/w500/'.$tv['poster_path']}}" alt="">

       <div class="mt-2">
         <a href="" class=" text-xl mt-2  hover:opacity-75 transition ease-in-out  hover:text-gray-300">{{$tv['name']}}</a>
             <div class="flex items-center text-gray-400 mt-1">
               <span>   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-red-600">
  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
</svg></span>
               <span class="ml-1">{{ $tv['vote_average'] * 10 . '%'}}</span>
               <span class="mx-2">|</span>
               {{-- <span>{{ \Carbon\Carbon::parse($tv['first_air_date'])->format('M d, Y')}}</span> --}}
             </div>
             <div class="text-gray-400 text-sm">
              {{-- {{$movie['genres']}}@if(!$loop->last) , @endif --}}
             </div>
         </div>
      </a>
 </div>
