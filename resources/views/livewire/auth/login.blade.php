<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />
        <x-mary-card>


            <x-passkey-verify />
            @if ($errors->any())
                <x-mary-alert  icon="o-exclamation-triangle" class="alert-warning alert-soft">
                    <ul>
                        @foreach($errors->all() as $message)
                            <li>{{$message}}</li>
                        @endforeach
                    </ul>
                </x-mary-alert>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
                @csrf

                <!-- Email Address -->
                <x-mary-input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="input-primary"
                    wire:model="email"
                    omit-error
                />

                <!-- Password -->
                <div class="relative">
                    <x-mary-input
                        name="password"
                        :label="__('Password')"
                        type="password"
                        required
                        autocomplete="current-password"
                        :placeholder="__('Password')"
                        viewable
                        class="input-primary"
                        wire:model="password"
                        first-error-only
                    />

                    @if (Route::has('password.request'))
                        <a class="absolute top-0 text-sm end-0 text-primary font-semibold" href="{{route('password.request')}}" wire:navigate>
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Remember Me -->
                <x-mary-checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" class="text-primary" />

                <div class="flex items-center justify-end">
                    <x-mary-button  type="submit" class="w-full btn-primary text-emerald-950" data-test="login-button">
                        {{ __('Log in') }}
                    </x-mary-button>
                </div>
            </form>


        </x-mary-card>
        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <a href="{{route('register')}}" wire:navigate class="link link-hover font-semibold text-primary">{{ __('Sign up') }}</a>
        </div>
    </div>
</x-layouts::auth>
