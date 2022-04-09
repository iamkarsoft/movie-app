<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mt-5 w-full mx-auto px-4 lg:w-4/5 mb-14 my-6">
              <h3 class="my-8 px-4 text-4xl font-extrabold text-orange-400">Upcoming Releases</h3>
        <div class="flex flex-col mt-6 w-full px-4">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-800 text-white font-bold p-4">
                                <tr>
                                    <th class="px-6 py-6 text-left text-xs font-medium  uppercase tracking-wider"
                                        scope="col">
                                        Name
                                    </th>
                                    <th class="px-6 py-6 text-left text-xs font-medium  uppercase tracking-wider"
                                        scope="col">
                                        Release Date
                                    </th>
                                    <th class="px-6 py-6 text-left text-xs font-medium  uppercase tracking-wider"
                                        scope="col">
                                        Last Air Date
                                    </th>
                                    <th class="px-6 py-6 text-left text-xs font-medium  uppercase tracking-wider"
                                        scope="col">
                                        Next Air Date
                                    </th>
                                     <th class="px-6 py-6 text-left text-xs font-medium  uppercase tracking-wider"
                                        scope="col">
                                        Status
                                    </th>
                                    <th class="relative px-6 py-3" scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($upcomings as $upcoming)
                                <tr >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-bold">
                                                {{$upcoming['name']}}
                                            </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{$upcoming['release_date']}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{$upcoming['last_air_date']}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{$upcoming['next_air_date']}}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                       @if($upcoming['watch_type']==0)
                                        <span class="">Watching</span>
                                           @elseif($upcoming['watch_type']==1)
                                           <span>Watched</span>
                                        @else
                                           <span>Abandoned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a class="text-indigo-600 hover:text-indigo-900" href="#">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                                <!-- More people... -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>

    </div>

         <div class="mt-5 w-full mx-auto px-4 lg:w-4/5 my-2 ">
              <h3 class="my-8 px-4 text-4xl text-red-700 font-extrabold mt-2">Upcoming Series</h3>
        <div class="flex flex-col mt-6 w-full px-4">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-800 text-white font-bold p-4">
                                <tr>
                                    <th class="px-6 py-6 text-left text-xs font-medium  uppercase tracking-wider"
                                        scope="col">
                                        Name
                                    </th>
                                    <th class="px-6 py-6 text-left text-xs font-medium  uppercase tracking-wider"
                                        scope="col">
                                        Release Date
                                    </th>
                                    <th class="px-6 py-6 text-left text-xs font-medium  uppercase tracking-wider"
                                        scope="col">
                                        Last Air Date
                                    </th>
                                    <th class="px-6 py-6 text-left text-xs font-medium  uppercase tracking-wider"
                                        scope="col">
                                        Next Air Date
                                    </th>
                                     <th class="px-6 py-6 text-left text-xs font-medium  uppercase tracking-wider"
                                        scope="col">
                                        Status
                                    </th>
                                    <th class="relative px-6 py-3" scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($episodes as $episode)
                                <tr >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-bold">
                                                {{$episode['name']}}
                                            </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{$episode['release_date']}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{$episode['last_air_date']}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{$episode['next_air_date']}}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                       @if($episode['watch_type']==0)
                                        <span class="">Watching</span>
                                           @elseif($episode['watch_type']==1)
                                           <span>Watched</span>
                                        @else
                                           <span>Abandoned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a class="text-indigo-600 hover:text-indigo-900" href="#">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                                <!-- More people... -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>

    </div>
    </div>
</x-app-layout>
