<?php

namespace App\Livewire\Api;

use App\Models\Api;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'API Name'],
            ['key' => 'url', 'label' => 'Endpoint'],
            ['key' => 'method', 'label' => 'Method'],
            ['key' => 'expected_status', 'label' => 'Expected'],
            ['key' => 'status', 'label' => 'Current'],
            ['key' => 'timeout', 'label' => 'Response'],

        ];
    }
    public function render()
    {
        return view('livewire.api.index', [
            'headers' => $this->headers(),
            // TODO(security): scope to authenticated user
            'apis' => Api::query()->with('latestCheck')->paginate(5),
        ]);
    }
}
