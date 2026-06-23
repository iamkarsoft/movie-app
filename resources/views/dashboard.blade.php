<x-app-layout>
    <x-slot name="header">
        <flux:heading size="xl" level="1">Dashboard</flux:heading>
        <flux:subheading class="text-zinc-400 mt-1">Upcoming releases and new episodes on your watchlist</flux:subheading>
    </x-slot>

    <div class="space-y-8">

        {{-- Upcoming Releases --}}
        <flux:card class="!bg-zinc-900 !border-zinc-800">
            <div class="flex items-center justify-between mb-5">
                <flux:heading size="lg" level="2">Upcoming Releases</flux:heading>
                <flux:badge color="rose" rounded>{{ count($upcomings) }}</flux:badge>
            </div>

            @if (count($upcomings) > 0)
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Name</flux:table.column>
                        <flux:table.column>Release Date</flux:table.column>
                        <flux:table.column>Last Air Date</flux:table.column>
                        <flux:table.column>Next Air Date</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                        <flux:table.column>Updated</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach ($upcomings as $upcoming)
                            <flux:table.row>
                                <flux:table.cell variant="strong">
                                    @if ($upcoming['type'] == 0 && !empty($upcoming['movie_id']))
                                        <a href="{{ URL::to('/movie/' . $upcoming['movie_id']) }}" target="_blank"
                                            class="hover:text-rose-400 transition-colors">
                                            {{ $upcoming['name'] }}
                                        </a>
                                    @elseif($upcoming['type'] == 1 && !empty($upcoming['movie_id']))
                                        <a href="{{ URL::to('/tvshow/' . $upcoming['movie_id']) }}" target="_blank"
                                            class="hover:text-violet-400 transition-colors">
                                            {{ $upcoming['name'] }}
                                        </a>
                                    @else
                                        {{ $upcoming['name'] }}
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell>{{ $upcoming['release_date'] }}</flux:table.cell>
                                <flux:table.cell>{{ $upcoming['last_air_date'] }}</flux:table.cell>
                                <flux:table.cell>{{ $upcoming['next_air_date'] }}</flux:table.cell>
                                <flux:table.cell>
                                    @if ($upcoming['watch_type'] == 0)
                                        <flux:badge color="blue" size="sm">Watching</flux:badge>
                                    @elseif($upcoming['watch_type'] == 1)
                                        <flux:badge color="green" size="sm">Watched</flux:badge>
                                    @else
                                        <flux:badge color="zinc" size="sm">Abandoned</flux:badge>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell>{{ $upcoming['updated_at']->diffForHumans() }}</flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            @else
                <flux:text class="text-zinc-500 py-4">No upcoming releases found.</flux:text>
            @endif
        </flux:card>

        {{-- Upcoming Series --}}
        <flux:card class="!bg-zinc-900 !border-zinc-800">
            <div class="flex items-center justify-between mb-5">
                <flux:heading size="lg" level="2">Upcoming Series</flux:heading>
                <flux:badge color="violet" rounded>{{ count($episodes) }}</flux:badge>
            </div>

            @if (count($episodes) > 0)
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Name</flux:table.column>
                        <flux:table.column>Release Date</flux:table.column>
                        <flux:table.column>Last Air Date</flux:table.column>
                        <flux:table.column>Next Air Date</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                        <flux:table.column>Updated</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach ($episodes as $episode)
                            <flux:table.row>
                                <flux:table.cell variant="strong">
                                    @if ($episode['type'] == 0 && !empty($episode['movie_id']))
                                        <a href="{{ URL::to('/movie/' . $episode['movie_id']) }}" target="_blank"
                                            class="hover:text-rose-400 transition-colors">
                                            {{ $episode['name'] }}
                                        </a>
                                    @elseif($episode['type'] == 1 && !empty($episode['movie_id']))
                                        <a href="{{ URL::to('/tvshow/' . $episode['movie_id']) }}" target="_blank"
                                            class="hover:text-violet-400 transition-colors">
                                            {{ $episode['name'] }}
                                        </a>
                                    @else
                                        {{ $episode['name'] }}
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell>{{ $episode['release_date'] }}</flux:table.cell>
                                <flux:table.cell>{{ $episode['last_air_date'] }}</flux:table.cell>
                                <flux:table.cell>{{ $episode['next_air_date'] }}</flux:table.cell>
                                <flux:table.cell>
                                    @if ($episode['watch_type'] == 0)
                                        <flux:badge color="blue" size="sm">Watching</flux:badge>
                                    @elseif($episode['watch_type'] == 1)
                                        <flux:badge color="green" size="sm">Watched</flux:badge>
                                    @else
                                        <flux:badge color="zinc" size="sm">Abandoned</flux:badge>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell>{{ $episode->updated_at->diffForHumans() }}</flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            @else
                <flux:text class="text-zinc-500 py-4">No upcoming episodes found.</flux:text>
            @endif
        </flux:card>

    </div>
</x-app-layout>
