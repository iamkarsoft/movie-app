<x-app-layout>
   <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Watch List') }}
        </h2>
    </x-slot>


    <div class="mt-5 w-full px-4 lg:w-2/5">
        <div class="flex flex-col mt-6">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        scope="col">
                                        Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        scope="col">
                                        Release Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        scope="col">
                                        Last Air Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        scope="col">
                                        Next Air Date
                                    </th>
                                    <th class="relative px-6 py-3" scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($watchlist as $item)
                                <tr >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-bold">
                                                {{$item['name']}}
                                            </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{$item['release_date']}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{$item['last_air_date']}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{$item['next_air_date']}}
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

</x-app-layout>
