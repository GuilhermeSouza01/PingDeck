<?php

namespace App\Livewire\Forms;

use App\ApiMethod;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ApiForm extends Form
{
    public string $name = '';

    public string $url = '';

    public string $method = ApiMethod::GET->value;

    public int $expected_status = 200;

    public int $timeout = 10;

    public ?string $description = null;

    public bool $is_active = true;

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'url', 'max:2048'],
            'method' => ['required', Rule::enum(ApiMethod::class)],
            'expected_status' => ['required', 'integer', 'between:100,599'],
            'timeout' => ['required', 'integer', 'between:1,255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * The model attributes to persist. It deliberately contains no "user_id":
     * ownership is derived from the authenticated user by the action.
     *
     * @return array{name: string, url: string, method: string, expected_status: int, timeout: int, description: string|null, is_active: bool}
     */
    public function attributes(): array
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
            'method' => $this->method,
            'expected_status' => $this->expected_status,
            'timeout' => $this->timeout,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];
    }
}
