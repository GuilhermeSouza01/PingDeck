<div>
    <div class="flex items-start justify-between">
        <x-mary-header title="APIs" subtitle="All registered endpoints being monitored by PingDeck." />
        <x-mary-button class="btn-primary text-emerald-950" icon="o-plus" wire:click="openCreateModal">
            {{ (__('Create API')) }}
        </x-mary-button>
    </div>
    <div class="rounded-xl border border-base-300 bg-base-100">

        {{-- TOOLBAR: busca + badges + filtro --}}
        <div class="flex items-center justify-between gap-4 p-4">
            <x-mary-input placeholder="Search by name or endpoint..." icon="o-magnifying-glass" class="max-w-sm" />

            <div class="flex items-center gap-1">
                <x-mary-badge value="All" class="badge-neutral" />
                <x-mary-badge value="Online" class="badge-ghost" />
                <x-mary-badge value="Slow" class="badge-ghost" />
                <x-mary-badge value="Offline" class="badge-ghost" />
                <x-mary-badge value="Paused" class="badge-ghost" />
            </div>

            <x-mary-button icon="o-adjustments-horizontal" label="Filters" class="btn-ghost" />
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
