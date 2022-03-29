<div>
     <section class="text-white font-extrabold">
                @if(session()->has('message'))
                    <span>{{ session('message')}}</span>
                @endif
            </section>
      <button wire:click="$emit('watchItem')"  class="inline-flex items-center bg-purple-500 text-gray-900 rounded font-semibold px-4 py-4 transition ease-in-out hover:bg-purple-600">
                  <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                  <span class="ml-2">Add to Watch List</span>
                </button>
</div>
