<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Watch List') }}
        </h2>
    </x-slot>



    <div class="mt-5 w-full mx-auto px-4 w-full">
        <div class="w-full flex justify-end px-20">

            <div class="w-40 flex-column bg-white border-2 border-slate-200  p-2 relative" x-data="{ filter: false }">
                <button x-on:click="filter=true" class="relative flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" />
                  </svg><span>Filter Movies</span></button>
                <ul x-show="filter" x-on:click.away="filter=false" class="border-t-2 bg-gray-100 z-50 border-slate-200 absolute w-40 lg:mt-2 lg:left-0">
                    <li class="text-gray-900 cursor-default select-none hover:bg-gray-200 relative py-2 pl-3 pr-9"><a href="{{ route('watchlist') }}" class="block">All</a></li>
                    <li class="text-gray-900 cursor-default select-none hover:bg-gray-200 relative py-2 pl-3 pr-9"> <a href="{{ route('watchlist', 1) }}"" class=" block">Watched</a></li>
                    <li class="text-gray-900 cursor-default select-none hover:bg-gray-200 relative py-2 pl-3 pr-9"> <a href="{{ route('watchlist', 0) }}"" class=" block">Watching</a></li>
                    <li class="text-gray-900 cursor-default select-none hover:bg-gray-200 relative py-2 pl-3 pr-9"><a href="{{ route('watchlist', 2) }}"" class=" block">Abandonned</a></li>
                </ul>

            </div>
        </div>
    </div>

    <div class="flex flex-col mt-6 w-full  px-4">
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
                                <th class="px-6 py-6 text-left text-xs font-medium  uppercase tracking-wider"
                                    scope="col">
                                    Update on
                                </th>
                                <th class="relative px-6 py-3" scope="col">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach ($watchlist as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold">

                                            @if ($item['type'] == 0)
                                                <a href="{{ URL::to('/movie/' . $item['movie_id']) }}"
                                                    target="_blank">{{ $item['name'] }}</a>
                                            @elseif($item['type'] == 1)
                                                <a href="{{ URL::to('/tvshow/' . $item['movie_id']) }}"
                                                    target="_blank">{{ $item['name'] }}</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $item['release_date'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $item['last_air_date'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item['next_air_date'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item['updated_at'] }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if ($item['watch_type'] == 0)
                                            <span class="">Watching</span>
                                        @elseif($item['watch_type'] == 1)
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
        <div class="p-4 livewire-pagination">
            {{ $watchlist->links() }}

        </div>
    </div>
    </div>

</x-app-layout>
