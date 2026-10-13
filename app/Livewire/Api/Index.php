<?php

namespace App\Livewire\Api;

use App\Actions\Api\CreateApi;
use App\ApiMethod;
use App\Livewire\Forms\ApiForm;
use App\Models\Api;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;
    use WithPagination;

    public ApiForm $form;

    public bool $creatingApi = false;

    /**
     * @return array<int, array{key: string, label: string}>
     */
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

    /**
     * @return array<int, array{id: string, name: string}>
     */
    public function methodOptions(): array
    {
        return array_map(
            fn (ApiMethod $method): array => ['id' => $method->value, 'name' => $method->value],
            ApiMethod::cases(),
        );
    }

    public function openCreateModal(): void
    {
        $this->form->reset();
        $this->resetValidation();

        $this->creatingApi = true;
    }

    public function createApi(CreateApi $action): void
    {
        $this->form->validate();

        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        $action->handle($user, $this->form->attributes());

        $this->creatingApi = false;
        $this->form->reset();
        $this->resetPage();

        $this->success(__('API created successfully.'));
    }

    public function render(): View
    {
        return view('livewire.api.index', [
            'headers' => $this->headers(),
            'methodOptions' => $this->methodOptions(),
            // TODO(security): scope to authenticated user
            'apis' => Api::query()->with('latestCheck')->paginate(5),
        ]);
    }
}
