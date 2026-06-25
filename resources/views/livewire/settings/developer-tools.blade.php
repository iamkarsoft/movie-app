<div x-data="queueWorker()" x-init="init()">

    {{-- Queue Worker Status Bar --}}
    <flux:card class="!bg-zinc-900 !border-zinc-800 mb-6">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-5 flex-wrap">
                {{-- Status dot + label --}}
                <div class="flex items-center gap-2">
                    <span
                        :class="running ? 'bg-emerald-400 shadow-[0_0_6px_1px_rgba(52,211,153,0.6)] animate-pulse' : 'bg-zinc-600'"
                        class="inline-block w-2.5 h-2.5 rounded-full transition-all duration-500">
                    </span>
                    <span class="text-zinc-300 text-sm font-medium" x-text="running ? 'Worker running' : 'Worker stopped'"></span>
                </div>

                {{-- Pending count --}}
                <button x-show="pending > 0" @click="showJobs = !showJobs"
                    class="flex items-center gap-1.5 text-indigo-400 hover:text-indigo-300 transition text-sm">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <span x-text="pending + (pending === 1 ? ' job queued' : ' jobs queued')"></span>
                </button>
                <span x-show="pending === 0 && !running" class="text-zinc-600 text-sm">No jobs queued</span>
                <span x-show="pending === 0 && running" class="text-zinc-500 text-xs">Waiting for jobs…</span>

                {{-- Failed warning --}}
                <div x-show="failed.length > 0" class="relative" x-data="{ errOpen: false }">
                    <button @click="errOpen = !errOpen"
                        class="flex items-center gap-1.5 text-red-400 hover:text-red-300 transition text-sm">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span x-text="failed.length + ' failed'"></span>
                    </button>
                    <div x-show="errOpen" @click.outside="errOpen = false" x-cloak
                        class="absolute left-0 top-8 z-50 w-80 bg-zinc-900 border border-red-900 rounded-xl shadow-2xl p-4 space-y-3">
                        <h4 class="text-xs font-semibold text-red-400 uppercase tracking-wide">Recent failures</h4>
                        <template x-for="f in failed" :key="f.uuid">
                            <div class="text-xs border-t border-zinc-800 pt-2 first:border-0 first:pt-0">
                                <div class="font-semibold text-zinc-200" x-text="f.job"></div>
                                <div class="text-red-300 mt-0.5 font-mono" x-text="f.error"></div>
                                <div class="text-zinc-600 mt-0.5" x-text="f.failed_at"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <flux:button x-show="pending > 0" @click="clearQueue()" size="sm" variant="ghost" class="text-red-400 hover:text-red-300">
                    Clear Queue
                </flux:button>
                <flux:button x-show="!running" @click="start()" x-bind:disabled="starting" size="sm" variant="filled">
                    <span x-text="starting ? 'Starting…' : 'Start Worker'">Start Worker</span>
                </flux:button>
                <flux:button x-show="running" @click="stop()" size="sm" variant="ghost">
                    Stop Worker
                </flux:button>
            </div>
        </div>

        {{-- Pending jobs list --}}
        <div x-show="showJobs && pendingJobs.length > 0" x-cloak
            class="border-t border-zinc-800 mt-4 pt-4 space-y-1.5">
            <p class="text-xs text-zinc-500 uppercase tracking-wide font-semibold mb-2">Queued jobs</p>
            <template x-for="j in pendingJobs" :key="j.id">
                <div class="flex items-center gap-3 text-xs">
                    <span :class="j.status === 'processing'
                            ? 'bg-indigo-900/60 text-indigo-300 border-indigo-700'
                            : 'bg-zinc-800 text-zinc-400 border-zinc-700'"
                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded border font-mono">
                        <span x-show="j.status === 'processing'" class="inline-block w-1.5 h-1.5 bg-indigo-400 rounded-full animate-pulse"></span>
                        <span x-show="j.status === 'waiting'" class="inline-block w-1.5 h-1.5 bg-zinc-500 rounded-full"></span>
                        <span x-text="j.job"></span>
                    </span>
                    <span class="text-zinc-600" x-text="j.status === 'processing' ? 'Processing…' : 'Waiting'"></span>
                    <button x-show="j.status === 'waiting'"
                        @click="cancelJob(j.id)"
                        class="ml-auto text-zinc-600 hover:text-red-400 transition-colors leading-none"
                        title="Cancel job">&times;</button>
                </div>
            </template>
        </div>

        {{-- Activity log --}}
        <div x-show="activity.length > 0" x-cloak
            class="border-t border-zinc-800 mt-4 pt-3 flex items-center gap-3 flex-wrap">
            <div class="flex items-center gap-3 shrink-0">
                <span class="text-zinc-600 text-xs">Recent:</span>
                <button @click="clearLog()" class="text-zinc-700 hover:text-zinc-400 transition text-xs underline underline-offset-2">Clear</button>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <template x-for="(a, i) in activity" :key="i">
                    <span :class="{
                            'bg-emerald-900/50 text-emerald-300 border-emerald-800': a.status === 'done',
                            'bg-indigo-900/50 text-indigo-300 border-indigo-800':   a.status === 'running',
                            'bg-red-900/50 text-red-300 border-red-800':             a.status === 'failed',
                        }"
                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded border text-xs font-mono shrink-0">
                        <span x-show="a.status === 'done'">✓</span>
                        <span x-show="a.status === 'running'" class="inline-block w-1.5 h-1.5 bg-indigo-400 rounded-full animate-pulse"></span>
                        <span x-show="a.status === 'failed'">✗</span>
                        <span x-text="a.job"></span>
                        <span class="opacity-50" x-text="a.time.slice(11, 19)"></span>
                    </span>
                </template>
            </div>
        </div>
    </flux:card>

    {{-- Commands --}}
    <div class="space-y-4">
        @foreach($commands as $command)
            @php $key = $command['key']; @endphp
            <flux:card class="!bg-zinc-900 !border-zinc-800">
                <div class="flex items-start justify-between gap-6">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-sm font-semibold text-white">{{ $command['name'] }}</h3>
                            @if(($statuses[$key] ?? null) === 'queued')
                                <flux:badge color="indigo" size="sm">Queued</flux:badge>
                            @endif
                        </div>
                        <p class="text-xs text-zinc-400 leading-relaxed">{{ $command['description'] }}</p>
                        <code class="mt-2 inline-block text-xs text-zinc-500 font-mono bg-zinc-800 px-2 py-0.5 rounded">php artisan {{ $key }}</code>
                    </div>

                    <div x-data="{ loading: false }">
                        <button
                            @click="loading = true; $wire.queueCommand('{{ $key }}').then(() => loading = false).catch(() => loading = false)"
                            x-bind:disabled="loading"
                            class="shrink-0 inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-colors bg-zinc-700 hover:bg-zinc-600 text-white disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg x-show="!loading" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="M6.3 2.84A1.5 1.5 0 0 0 4 4.11v11.78a1.5 1.5 0 0 0 2.3 1.27l9.344-5.891a1.5 1.5 0 0 0 0-2.538L6.3 2.84Z" />
                            </svg>
                            <svg x-show="loading" x-cloak class="size-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-show="!loading">Run</span>
                            <span x-show="loading" x-cloak>Dispatching…</span>
                        </button>
                    </div>
                </div>
            </flux:card>
        @endforeach
    </div>

</div>

<script>
function queueWorker() {
    return {
        running:     false,
        pending:     0,
        pendingJobs: [],
        failed:      [],
        activity:    [],
        starting:    false,
        showJobs:    false,
        _timer:      null,

        init() {
            this.poll();
            this._timer = setInterval(() => this.poll(), 3000);
        },

        async poll() {
            try {
                const r = await fetch('{{ route('dev-tools.queue.status') }}');
                const d = await r.json();
                this.running     = d.running;
                this.pending     = d.pending;
                this.pendingJobs = d.pendingJobs ?? [];
                this.failed      = d.failed ?? [];
                this.activity    = d.activity ?? [];
                if (this.running) this.starting = false;
                if (this.pending === 0) this.showJobs = false;
            } catch (_) {}
        },

        async start() {
            this.starting = true;
            await fetch('{{ route('dev-tools.queue.start') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            });
            setTimeout(() => this.poll(), 500);
            setTimeout(() => this.poll(), 1500);
            setTimeout(() => this.poll(), 3000);
            setTimeout(() => { this.starting = false; }, 8000);
        },

        async stop() {
            await fetch('{{ route('dev-tools.queue.stop') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            });
            setTimeout(() => this.poll(), 800);
        },

        async clearLog() {
            await fetch('{{ route('dev-tools.queue.clear-log') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            });
            this.activity = [];
        },

        async cancelJob(id) {
            await fetch(`{{ url('settings/dev-tools/queue/job') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            });
            this.pendingJobs = this.pendingJobs.filter(j => j.id !== id);
            this.pending = this.pendingJobs.length;
            if (this.pending === 0) this.showJobs = false;
        },

        async clearQueue() {
            await fetch('{{ route('dev-tools.queue.clear') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            });
            this.pending = 0;
            this.pendingJobs = [];
            this.showJobs = false;
            await this.poll();
        },
    };
}
</script>
