<div>
    <div class="flex flex-col">
        <div>
            <span class="bold text-xl mr-4">Season:</span>
            {{ is_null($season) ? 'Not Started' : $season }}
        </div>
        <div>
            <span class="bold text-xl mr-4">Episode:</span>
            {{ is_null($episode) ? 'Not Started' : $episode }}
        </div>
    </div>

    <button wire:click="show" class="mt-2 rounded bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
        Update Episodes
    </button>

    <x-modal-wrapper wire:model="visible">
        <form wire:submit="saveData" class="p-4 min-w-[18rem]">

            <div class="my-4">
                <label class="block text-sm font-medium leading-6 text-white">Season</label>
                <div class="mt-2">
                    <input type="number" wire:model.live="season"
                        class="block w-full px-2 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        placeholder="0">
                </div>
            </div>

            <div class="my-4">
                <label class="block text-sm font-medium leading-6 text-white">Episode</label>
                <div class="mt-2">
                    <input type="number" wire:model.live="episode"
                        class="block w-full px-2 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        placeholder="0">
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="rounded bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Save
                </button>
                <button type="button" wire:click="hide"
                    class="rounded bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                    Cancel
                </button>
            </div>
        </form>
    </x-modal-wrapper>
</div>
