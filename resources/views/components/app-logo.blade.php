@props([
    'sidebar' => false,
])

<div {{ $attributes->class('flex items-center') }}>
    <x-app-logo-icon class="{{ $sidebar ? 'h-8' : 'h-12' }} w-auto" />
</div>
