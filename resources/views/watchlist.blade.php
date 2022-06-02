<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Watch List') }}
        </h2>
    </x-slot>



    <div class="w-full px-4 mx-auto mt-5">
        <div class="flex justify-end w-full px-20">

            <div class="relative w-40 p-2 bg-white border-2 flex-column border-slate-200" x-data="{ filter: false }">
                <button x-on:click="filter=true" class="relative flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" />
                  </svg><span>Filter Movies</span></button>
                <ul x-show="filter" x-on:click.away="filter=false" class="absolute z-50 w-40 bg-gray-100 border-t-2 border-slate-200 lg:mt-2 lg:left-0">
                    <li class="relative py-2 pl-3 text-gray-900 cursor-default select-none hover:bg-gray-200 pr-9"><a href="{{ route('watchlist') }}" class="block">All</a></li>
                    <li class="relative py-2 pl-3 text-gray-900 cursor-default select-none hover:bg-gray-200 pr-9"> <a href="{{ route('watchlist', 1) }}"" class="block ">Watched</a></li>
                    <li class="relative py-2 pl-3 text-gray-900 cursor-default select-none hover:bg-gray-200 pr-9"> <a href="{{ route('watchlist', 0) }}"" class="block ">Watching</a></li>
                    <li class="relative py-2 pl-3 text-gray-900 cursor-default select-none hover:bg-gray-200 pr-9"><a href="{{ route('watchlist', 2) }}"" class="block ">Abandonned</a></li>
                </ul>

            </div>
        </div>
    </div>

    <div class="flex flex-col w-full px-4 mt-6">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="p-4 font-bold text-white bg-gray-800">
                            <tr>
                                <th class="px-6 py-6 text-xs font-medium tracking-wider text-left uppercase"
                                    scope="col">
                                    Name
                                </th>
                                <th class="px-6 py-6 text-xs font-medium tracking-wider text-left uppercase"
                                    scope="col">
                                    Release Date
                                </th>
                                <th class="px-6 py-6 text-xs font-medium tracking-wider text-left uppercase"
                                    scope="col">
                                    Last Air Date
                                </th>
                                <th class="px-6 py-6 text-xs font-medium tracking-wider text-left uppercase"
                                    scope="col">
                                    Next Air Date
                                </th>
                                <th class="px-6 py-6 text-xs font-medium tracking-wider text-left uppercase"
                                    scope="col">
                                    Status
                                </th>
                                <th class="px-6 py-6 text-xs font-medium tracking-wider text-left uppercase"
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
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $item['next_air_date'] }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $item['updated_at'] }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        @if ($item['watch_type'] == 0)
                                            <span class="">Watching</span>
                                        @elseif($item['watch_type'] == 1)
                                            <span>Watched</span>
                                        @else
                                            <span>Abandoned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
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
