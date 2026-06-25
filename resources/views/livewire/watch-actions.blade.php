@php
    $statusLabels = [1 => 'Watching', 2 => 'Watched', 3 => 'Abandoned'];
    $currentStatus = $movie_db['watch_type'] ?? 0;
@endphp
<div x-data="{ open: false }" class="relative">
    <button
        @click="open = !open"
        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg transition-colors bg-zinc-700 hover:bg-zinc-600 text-white"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
            <path d="M10 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
            <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 0 1 0-1.186A10.004 10.004 0 0 1 10 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0 1 10 17c-4.257 0-7.893-2.66-9.336-6.41ZM14 10a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" clip-rule="evenodd" />
        </svg>
        {{ $statusLabels[$currentStatus] ?? 'Watch Status' }}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-3 opacity-60">
            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
    </button>
    <ul
        x-show="open"
        @click.away="open = false"
        x-cloak
        class="absolute z-50 mt-1 left-0 w-44 bg-zinc-800 rounded-lg shadow-xl ring-1 ring-white/10 overflow-hidden"
    >
        <li wire:click="$dispatch('status',[1])" @click="open = false"
            class="flex items-center gap-2 px-4 py-2.5 text-sm cursor-pointer hover:bg-zinc-700 {{ $currentStatus == 1 ? 'text-white font-semibold' : 'text-zinc-300' }}">
            @if($currentStatus == 1)
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 text-rose-400 shrink-0">
                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
            @else
                <div class="size-4 shrink-0"></div>
            @endif
            Watching
        </li>
        <li wire:click="$dispatch('status',[2])" @click="open = false"
            class="flex items-center gap-2 px-4 py-2.5 text-sm cursor-pointer hover:bg-zinc-700 {{ $currentStatus == 2 ? 'text-white font-semibold' : 'text-zinc-300' }}">
            @if($currentStatus == 2)
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 text-rose-400 shrink-0">
                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
            @else
                <div class="size-4 shrink-0"></div>
            @endif
            Watched
        </li>
        <li wire:click="$dispatch('status',[3])" @click="open = false"
            class="flex items-center gap-2 px-4 py-2.5 text-sm cursor-pointer hover:bg-zinc-700 {{ $currentStatus == 3 ? 'text-white font-semibold' : 'text-zinc-300' }}">
            @if($currentStatus == 3)
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 text-rose-400 shrink-0">
                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
            @else
                <div class="size-4 shrink-0"></div>
            @endif
            Abandoned
        </li>
    </ul>
</div>
