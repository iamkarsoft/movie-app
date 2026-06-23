<x-app-layout>
    <x-slot name="header">
        <flux:heading size="xl" level="1">Watch List</flux:heading>
        <flux:subheading class="text-zinc-400 mt-1">Everything you're tracking, watching, or have watched</flux:subheading>
    </x-slot>

    <flux:card class="!bg-zinc-900 !border-zinc-800">
        <livewire:movie-data-table model="App\Models\MovieUser" paginate="15" />
    </flux:card>
</x-app-layout>
