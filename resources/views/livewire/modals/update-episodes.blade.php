<x-modal-wrapper wire:model="visible" class="w-40">
    <form  wire:submit="saveData" class="p-4 mt-4">

        <div class="my-4">
        <label for="season" class="block text-sm font-medium leading-6 text-gray-900">Season</label>
        <div class="mt-2">
        <input type="number" wire:model.live="season" class="block w-full px-2 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="0">
        </div>
        </div>

        <div class="my-4">
            <label for="episode" class="block text-sm font-medium leading-6 text-gray-900">Episode</label>
            <div class="mt-2">
            <input type="number" wire:model.live="episode" class="block w-full px-2 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="0">
            </div>
        </div>

        <div>
            <button type="Submit" x-on:click="$wire.saveData(); Livewire.dispatchTo('{{ $this->getName() }}', 'hide');" class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Save
            </button>

             <button type="Submit" wire:click="closeModal" class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Cancel
            </button>
        </div>
    </form>
</x-modal-wrapper>
