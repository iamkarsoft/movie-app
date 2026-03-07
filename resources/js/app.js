import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
// Register any Alpine directives, components, or plugins here...
Alpine.plugin(ToastComponent)
Livewire.start()

import './bootstrap';
import '../css/app.css';
