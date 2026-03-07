  <div
        x-data="{visible: @entangle($attributes->wire('model')), now: null }"
        x-show="visible"
        style="background-color: rgba(0,0,0,0.5);"
        class="fixed top-0 left-0 flex items-center w-full h-full overflow-y-auto shadow-lg" x-show="isOpen"
        x-cloak
    >
    <div class="container mx-auto overflow-y-hidden rounded-lg lg:px-32">
        <div class="bg-gray-900 rounded">
            <div class="flex justify-end pt-2 pr-4">
                <button class="text-3xl leading-none hover:text-gray-300 close-modal"
                    @click="isOpen=false;stopVideos()">&times;
                </button>
            </div>
            <div class="px-8 py-8 modal-body">
                {{ $slot }}
                <button x-on:click="visible = false">Close</button>
            </div>
        </div>
    </div>
</div> <!-- modal end -->
