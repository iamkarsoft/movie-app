<div>
    <div class="w-full my-2 flex mx-4">
        <div class="">
            <input class="w-full mx-4 p-2" type="search" wire:model.live="query" placeholder="Search" />
        </div>
    </div>


    <div class="flex flex-col w-full px-8 mt-6">
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

                            @foreach ($this->records() as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold flex gap-2 cursor-pointer">
                                            @if ($item['type'] == 0 && !is_null($item['movie_id'])  )
                                                <a href="{{ URL::to('/movie/' . $item['movie_id']) }}"
                                                    target="_blank">{{ $item['name'] }}</a>
                                            @elseif($item['type'] == 1 && !is_null($item['movie_id']) )
                                                <a href="{{ URL::to('/tvshow/' . $item['movie_id']) }}"
                                                    target="_blank">{{ $item['name'] }}</a>
                                            @else
                                                {{ $item['name'] }}
                                            @endif

                                            @if(!is_null($item['movie_id']))
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                                <path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 0 0 1.06 0l7.22-7.22v5.69a.75.75 0 0 0 1.5 0v-7.5a.75.75 0 0 0-.75-.75h-7.5a.75.75 0 0 0 0 1.5h5.69l-7.22 7.22a.75.75 0 0 0 0 1.06Z" clip-rule="evenodd" />
                                                </svg>
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
                                        @if ($item['watch_type'] == 0)
                                            <span class="">Added to watch list</span>
                                        @elseif($item['watch_type'] == 1)
                                             <span class="">Watching</span>
                                        @elseif($item['watch_type'] == 2)
                                            <span>Watched</span>
                                        @else
                                            <span>Abandoned</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $item['updated_at'] }}
                                    </td>

                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                        <a class="text-indigo-600 hover:text-indigo-900" href="#">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="p-4 livewire-pagination">
            {{ $this->records()->links() }}
        </div>
    </div>
</div>
