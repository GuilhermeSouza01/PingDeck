<div>
    <div class="flex items-start justify-between">
        <x-mary-header title="APIs" subtitle="All registered endpoints being monitored by PingDeck." />
        <x-mary-button class="btn-primary text-emerald-950" icon="o-plus" wire:click="openCreateModal">
            {{ (__('Create API')) }}
        </x-mary-button>
    </div>
    <div class="rounded-xl border border-base-300 bg-base-100">

        {{-- TOOLBAR: busca + filtros --}}
        <div class="flex flex-col gap-4 p-4 md:flex-row md:items-center md:justify-between">
            <x-mary-input
                placeholder="Search by name or endpoint..."
                icon="o-magnifying-glass"
                wire:model.live.debounce.300ms="search"
                clearable
                class="max-w-sm"
            />

            <div class="flex items-center gap-2">
                <x-mary-select
                    placeholder="All methods"
                    placeholder-value=""
                    :options="$methodOptions"
                    wire:model.live="method"
                    class="w-40"
                />

                <x-mary-select
                    placeholder="All statuses"
                    placeholder-value=""
                    :options="$statusOptions"
                    wire:model.live="status"
                    class="w-40"
                />
            </div>
        </div>

        {{-- DIVISOR --}}
        <div class="border-t border-base-300"></div>
        {{-- TABELA --}}
        <x-mary-table
            :headers="$headers"
            :rows="$apis"
            with-pagination
        >
            @scope('cell_expected_status', $api)
                <span class="font-mono text-xs opacity-70">{{ $api->expected_status }}</span>
            @endscope
            @scope('cell_status', $api)
                @if($api->latestCheck)
                    <x-mary-badge class="{{ $api->latestCheck->status->color() }} gap-1.5 badge-soft">
                        <span class="size-1.5 rounded-full bg-current"></span>
                        {{ $api->latestCheck->status->label() }}
                    </x-mary-badge>
                @else
                    <x-mary-badge class="badge-ghost gap-1.5">{{ __('No checks yet') }}</x-mary-badge>
                @endif
            @endscope
        </x-mary-table>

    </div>

    {{-- CREATE API MODAL --}}
    <x-mary-modal wire:model="creatingApi" title="Create API" subtitle="Register a new endpoint to monitor." separator>
        <x-mary-form wire:submit="createApi" no-separator>
            <x-mary-input label="Name" wire:model="form.name" icon="o-tag" required />

            <x-mary-input label="URL" wire:model="form.url" icon="o-link" type="url" required />

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-mary-select label="Method" wire:model="form.method" :options="$methodOptions" />
                <x-mary-input label="Expected status" wire:model="form.expected_status" type="number" />
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-mary-input label="Timeout (seconds)" wire:model="form.timeout" type="number" />
                <x-mary-toggle label="Active" wire:model="form.is_active" right />
            </div>

            <x-mary-textarea label="Description" wire:model="form.description" rows="3" />

            <x-slot:actions>
                <x-mary-button label="Cancel" @click="$wire.creatingApi = false" />
                <x-mary-button label="Create" class="btn-primary" type="submit" spinner="createApi" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-modal>
</div>
