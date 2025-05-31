<div class="mt-8">
    @php
         $searchTitle = urlencode($tv['name']);
    @endphp
    <a href="{{route('tv.show',$tv['id'])}}">

        <div class="mt-2 rounded-lg overflow-hidden bg-white text-black p-2">

            <img class="h-80" src="{{'https://image.tmdb.org/t/p/w500'.$tv['poster_path']}}" alt="">

            <div class="h-14 my-2">
                <a href="{{route('tv.show',$tv['id'])}}"
                   class=" text-base mt-2  hover:opacity-75 transition ease-in-out  hover:text-gray-300">{{$tv['name']}}</a>
            </div>
            <div class="flex gap-2 my-2">
                <div class="flex items-center text-gray-400 mt-1">
               <span>   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             fill="currentColor" class="w-6 h-6 text-red-600">
             <path
                 d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg></span>
                    <span class="ml-1">{{ $tv['vote_average'] * 10 . '%'}}</span>

                </div>

                <div class="flex items-center mx-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>

                    <span class="">{{ ceil($tv['popularity'] )}}</span>
                </div>
            </div>


            <div class="flex items-center my-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
                <span>{{ \Carbon\Carbon::parse($tv['first_air_date'])->format('M d, Y')}}</span>

            </div>

            <div class="text-gray-400 text-sm  gap-2 my-2 flex">
                <a href="https://lookmovie2.to/shows/search/?q={{$searchTitle}}" target="_blank"
                   class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>

                    <span>1</span>
                </a>

                <a href="https://sflix.to/search/{{\Str::kebab($tv['name'])}}" target="_blank"
                   class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 text-blue-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>

                    <span>2</span>
                </a>
            </div>
        </div>
    </a>
</div>
