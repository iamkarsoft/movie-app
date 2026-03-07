<div
    x-data="{ visible: $wire.entangle('{{ $attributes->wire('model')->value() }}') }"
    x-show="visible"
    x-cloak
    style="background-color: rgba(0,0,0,0.5);"
    class="fixed inset-0 z-50"
>
    <div
        class="fixed top-0 left-0 flex items-center w-full h-full overflow-y-auto shadow-lg"
    >
        <div class="container mx-auto overflow-y-hidden rounded-lg lg:px-32">
            <div class="bg-gray-900 rounded">
                <div class="flex justify-end pt-2 pr-4">
                    <button class="text-3xl leading-none hover:text-gray-300 close-modal"
                        @click="visible = false">&times;
                    </button>
                </div>
                <div class="px-8 py-8 modal-body">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div> <!-- modal end -->
