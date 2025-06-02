import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import ToastComponent from '../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts'
// Register any Alpine directives, components, or plugins here...
Alpine.plugin(ToastComponent)
Livewire.start()

import './bootstrap';
import '../css/app.css';
