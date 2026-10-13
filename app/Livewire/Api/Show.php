<?php

namespace App\Livewire\Api;

use App\Models\Api;
use Livewire\Component;

class Show extends Component
{
    public Api $api;

    public function mount(Api $api): void
    {
        // TODO(security): scope to authenticated user
        $this->api = $api;
    }
    public function render()
    {
        return view('livewire.api.show');
    }
}
