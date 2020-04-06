@extends('layout.app')
@section('content')
  <div class="movie-info border-b border-gray-800">
    <div class="flex-none mx-auto px-4 py-16 flex flex-col md:flex-row">
      <img src="" alt="">
      <div class="md:ml-24 px-8">
          <h2 class="text-4xl font-semibold">Parasite (2019)</h2>

             <div class="flex flex-wrap items-center text-gray-400 mt-1">
                <span>star</span>
                <span class="ml-1">85%</span>
                <span class="mx-2">|</span>
                <span>feb 25,2020</span>
               <span class="mx-2">|</span>
              <span class="">
                Action, Thriller, Comedy.
              </span>
            </div>
            <p class="text-gray-300 mt-8">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam nisi fuga amet libero, beatae incidunt, sint, maiores eius doloribus fugit sunt! Voluptates magni saepe laborum cupiditate unde ab? Laboriosam quis, rerum, eaque a ab, quam harum fugit possimus sequi numquam veritatis, repellat est. Rem, veniam consectetur commodi facere, placeat doloribus similique fugiat numquam. Quidem porro atque maiores exercitationem quod, delectus ea molestiae est cumque, pariatur ad! Assumenda sit doloremque, facilis consequuntur dicta eum, necessit atibus quas sapiente hic quo tempore, eligendi natus, voluptate laboriosam perspiciatis minus modi soluta aspernatur! Consequatur animi, esse non rerum earum sint blanditiis cumque nulla perferendis repudiandae!
            </p>

            <div class="mt-12">
              <h4 class="text-white font-semibold">Cast</h4>
              <div class="flex mt-4">
                <div class="mx-4">
                  <div>BOong</div>
                  <div>screenplay, Directore</div>
                </div>
                <div class="mx-4">
                  <div>BOong</div>
                  <div>screenplay, Directore</div>
                </div>
              </div>
            </div>

            <div class="mt-12">
              <button class="flex items-center bg-orange-500 text-gray-900 rounded font-semibold px-4 py-4 transition ease-in-out hover:bg-orange-600">
                <span class="ml-2">Play Trailer</span>
              </button>
            </div>
      </div>
    </div>

  </div><!-- end  movie info-->
<div class="movie-cast border-b border-gray-800">
  <div class="container mx-auto px-4 py-16">
    <h2 class="text-4xl font-semibold">Cast</h2>
  </div>
        <div class="grid grid-cols-1 sm:gri d-cols-2 md:grid-cols-3 lg:grid-cols-4 x gap-8 px-4 ">
        <div class="mt-4">
          <a href="" class=" text-lg mt-2  hover:opacity-75 transition ease-in-out  hover:text-gray-300"><img src="" alt="parasite"></a>


              <div class="">
                Action,Thriller,Comedy
              </div>
          </div>

        <div class="mt-8">
          <a href="" class=" text-lg mt-2  hover:opacity-75 transition ease-in-out  hover:text-gray-300"><img src="" alt="parasite"></a>


              <div class="">
                Action,Thriller,Comedy
              </div>
          </div>

        <div class="mt-8">
          <a href="" class=" text-lg mt-2  hover:opacity-75 transition ease-in-out  hover:text-gray-300"><img src="" alt="parasite"></a>


              <div class="">
                Action,Thriller,Comedy
              </div>
          </div>

        <div class="mt-8">
          <a href="" class=" text-lg mt-2  hover:opacity-75 transition ease-in-out  hover:text-gray-300"><img src="" alt="parasite"></a>


              <div class="">
                Action,Thriller,Comedy
              </div>
          </div>

        </div>
</div>
@endsection