<?php

namespace App\Livewire\Api;

use App\Models\Api;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Show extends Component
{
    public Api $api;

    public function mount(Api $api): void
    {
        Gate::authorize('view', $api);

        $this->api = $api;
    }

    public function render(): View
    {
        return view('livewire.api.show');
    }
}
