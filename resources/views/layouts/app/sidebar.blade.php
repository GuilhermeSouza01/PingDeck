<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="forest">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen font-sans antialiased bg-base-200">


{{-- NAVBAR --}}
<x-mary-nav sticky full-width>

    <x-slot:brand>

        {{-- Mobile sidebar toggle --}}
        <label for="main-drawer" class="lg:hidden mr-3">
            <x-mary-icon name="o-bars-3" class="cursor-pointer" />
        </label>

        <x-app-logo
            href="{{ route('dashboard') }}"
            wire:navigate
        />

    </x-slot:brand>


    <x-slot:actions>

        <x-mary-button
            icon="o-bell"
            class="btn-ghost btn-circle"
        />
        <x-mary-theme-toggle darkTheme="forest" lightTheme="emerald" />

    </x-slot:actions>

</x-mary-nav>



{{-- MAIN --}}
<x-mary-main with-nav full-width>


    {{-- SIDEBAR --}}
    <x-slot:sidebar
        drawer="main-drawer"
        wire:navigate
    >

        <div class="flex h-full flex-col">


{{--            --}}{{-- LOGO --}}
{{--            <div class="px-5 py-5">--}}

{{--                <x-app-logo--}}
{{--                    :sidebar="true"--}}
{{--                    href="{{ route('dashboard') }}"--}}
{{--                    wire:navigate--}}
{{--                />--}}

{{--            </div>--}}


{{--            <x-mary-menu-separator />--}}


            {{-- MENU --}}
            <x-mary-menu
                activate-by-route
                active-bg-color="bg-primary/30 dark:text-primary font-bold"
                class="font-bold"
            >

                <x-mary-menu-item
                    title="Dashboard"
                    icon="o-squares-2x2"
                    link="{{ route('dashboard') }}"
                />


                <x-mary-menu-item
                    title="APIs"
                    icon="o-signal"
                    link="{{ route('apis.index') }}"
                />


            </x-mary-menu>



            {{-- USER FOOTER --}}
            <div class="mt-auto">

                <x-mary-menu-separator />


                <div class="p-2">

                    <div class="flex items-center justify-between rounded-xl">

                        <x-mary-avatar
                            :title="auth()->user()->name"
                            :subtitle="auth()->user()->email"
                            :placeholder="
                                Str::of(auth()->user()->name)
                                ->explode(' ')
                                ->map(fn($n) => $n[0])
                                ->take(2)
                                ->join('')
                            "
                            class="!w-10"
                        />


                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <x-mary-button
                                type="submit"
                                icon="o-arrow-right-on-rectangle"
                                class="btn-ghost btn-circle"
                            />

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
