<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">

{{-- NAVBAR mobile only --}}
<x-mary-nav sticky class="lg:hidden">
    <x-slot:brand>
        App
    </x-slot:brand>
    <x-slot:actions>
        <label for="main-drawer" class="lg:hidden mr-3">
            <x-mary-icon name="o-envelope" class="cursor-pointer" />
        </label>
    </x-slot:actions>
</x-mary-nav>

{{-- MAIN --}}
<x-mary-main full-width>

    {{-- SIDEBAR --}}
    <x-slot:sidebar drawer="main-drawer" class="bg-zinc-50 dark:bg-zinc-900">
        <div class="flex h-full flex-col">


            {{-- LOGO --}}
            <div class="ml-5 pt-5">
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate class="cursor-pointer" />
            </div>
            <x-mary-menu-separator />
            {{-- MENU --}}
            <x-mary-menu activate-by-route active-bg-color="bg-primary/30 font-bold" >
                <x-mary-menu-item title="{{ __('Dashboard') }}" icon="o-squares-2x2" link="{{ route('dashboard') }}"  class="text-primary" />
            </x-mary-menu>
            <div class="mt-auto">
                <x-mary-menu-separator />
                <div class="p-2">
                   <div class="flex items-center justify-between rounded-xl p-2">
                       <div class="flex items-center gap-3">
                           {{-- Avatar --}}
                           <x-mary-avatar
                               :title="auth()->user()->name"
                               :placeholder="Str::of(auth()->user()->name)->explode(' ')->map(fn($n) => $n[0])->take(2)->join('')"
                               :subtitle="auth()->user()->email"
                               class="!w-10"
                           />
                       </div>
                       {{-- Logout --}}
                       <form method="POST" action="{{ route('logout') }}">
                           @csrf

                           <x-mary-button
                               type="submit"
                               class="btn btn-ghost btn-circle btn-sm hover:bg-primary/30 hover:text-primary transition easy-in-out"
                               title="Logout"
                           >
                               <x-mary-icon name="o-arrow-right-on-rectangle" class="w-5 h-5" />
                           </x-mary-button>
                       </form>
                   </div>
                </div>

            </div>
        </div>
    </x-slot:sidebar>

    {{-- CONTENT --}}
    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-mary-main>

<x-mary-toast />
</body>
</html>
