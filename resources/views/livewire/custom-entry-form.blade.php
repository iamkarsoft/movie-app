<div>
    @if ($openOnLoad)
        <div x-data x-init="$nextTick(() => $flux.modal('{{ \App\Livewire\CustomEntryForm::MODAL }}').show())"></div>
    @endif

    <flux:modal :name="\App\Livewire\CustomEntryForm::MODAL" class="w-full max-w-lg">
        <form wire:submit="save" class="space-y-5">
            <div>
                <flux:heading size="lg">{{ $movieId ? 'Edit custom entry' : 'Add custom entry' }}</flux:heading>
                <flux:subheading class="text-zinc-400">
                    For movies and series that aren't on TMDB. You manage the dates yourself; they aren't refreshed automatically.
                </flux:subheading>
            </div>

            <flux:input wire:model="name" label="Title" placeholder="e.g. Local drama series" required autofocus />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:select wire:model.live="type" label="Type">
                    @foreach ($typeLabels as $value => $label)
                        <flux:select.option :value="$value">{{ $label }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="watch_type" label="Status">
                    <flux:select.option :value="\App\Models\Movie::onWatchlist">Listed</flux:select.option>
                    <flux:select.option :value="\App\Models\Movie::Watching">Watching</flux:select.option>
                    <flux:select.option :value="\App\Models\Movie::Watched">Watched</flux:select.option>
                    <flux:select.option :value="\App\Models\Movie::Abandoned">Abandoned</flux:select.option>
                </flux:select>
            </div>

            <flux:input wire:model="release_date" type="date"
                :label="$this->isEpisodic() ? 'Premiere date' : 'Release date'" />

            @if ($this->isEpisodic())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="last_air_date" type="date" label="Last episode aired" />
                    <flux:input wire:model="next_air_date" type="date" label="Next episode airs" />
                </div>
            @endif

            <div class="flex items-center gap-2 pt-2">
                @if ($movieId)
                    <flux:button type="button" variant="danger" wire:click="delete"
                        wire:confirm="Delete this custom entry? This can't be undone.">
                        Delete
                    </flux:button>
                @endif
                <flux:spacer />
                <flux:modal.close>
                    <flux:button type="button" variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" class="!bg-rose-600 hover:!bg-rose-500 !text-white !border-0">{{ $movieId ? 'Save changes' : 'Add entry' }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
