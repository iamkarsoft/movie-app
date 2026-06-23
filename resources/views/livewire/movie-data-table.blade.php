<div>
    <div class="mb-4 flex items-center gap-3">
        <div class="flex-1 min-w-0">
            <flux:input
                wire:model.live.debounce.400ms="query"
                :loading="false"
                type="text"
                placeholder="Search your list…"
                icon="magnifying-glass"
                clearable
            />
        </div>
        <flux:select wire:model.live="statusFilter" class="w-44 shrink-0">
            <flux:select.option value="">All statuses</flux:select.option>
            <flux:select.option value="0">Listed</flux:select.option>
            <flux:select.option value="1">Watching</flux:select.option>
            <flux:select.option value="2">Watched</flux:select.option>
            <flux:select.option value="3">Abandoned</flux:select.option>
        </flux:select>
    </div>

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
            @foreach ($this->records() as $item)
                <flux:table.row wire:key="record-{{ $item['id'] ?? $loop->index }}">
                    <flux:table.cell variant="strong">
                        @if ($item['type'] == 0 && !is_null($item['movie_id']))
                            <a href="{{ URL::to('/movie/' . $item['movie_id']) }}" target="_blank"
                                class="inline-flex items-center gap-1 hover:text-rose-400 transition-colors">
                                {{ $item['name'] }}
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-3 text-zinc-500 shrink-0">
                                    <path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 0 0 1.06 0l7.22-7.22v5.69a.75.75 0 0 0 1.5 0v-7.5a.75.75 0 0 0-.75-.75h-7.5a.75.75 0 0 0 0 1.5h5.69l-7.22 7.22a.75.75 0 0 0 0 1.06Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @elseif($item['type'] == 1 && !is_null($item['movie_id']))
                            <a href="{{ URL::to('/tvshow/' . $item['movie_id']) }}" target="_blank"
                                class="inline-flex items-center gap-1 hover:text-violet-400 transition-colors">
                                {{ $item['name'] }}
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-3 text-zinc-500 shrink-0">
                                    <path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 0 0 1.06 0l7.22-7.22v5.69a.75.75 0 0 0 1.5 0v-7.5a.75.75 0 0 0-.75-.75h-7.5a.75.75 0 0 0 0 1.5h5.69l-7.22 7.22a.75.75 0 0 0 0 1.06Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @else
                            {{ $item['name'] }}
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>{{ $item['release_date'] }}</flux:table.cell>
                    <flux:table.cell>{{ $item['last_air_date'] }}</flux:table.cell>
                    <flux:table.cell>{{ $item['next_air_date'] }}</flux:table.cell>
                    <flux:table.cell>
                        @if ($item['watch_type'] == 0)
                            <flux:badge color="zinc" size="sm">Listed</flux:badge>
                        @elseif($item['watch_type'] == 1)
                            <flux:badge color="blue" size="sm">Watching</flux:badge>
                        @elseif($item['watch_type'] == 2)
                            <flux:badge color="green" size="sm">Watched</flux:badge>
                        @else
                            <flux:badge color="red" size="sm">Abandoned</flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>{{ $item['updated_at'] }}</flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <flux:pagination :paginator="$this->records()" class="mt-4" />
</div>
