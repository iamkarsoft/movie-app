<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Watch List') }}
        </h2>
    </x-slot>

    <livewire:movie-data-table model="App\Models\MovieUser" paginate="10" />

</x-app-layout>
