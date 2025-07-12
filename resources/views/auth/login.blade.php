<x-guest-layout>
    <div class="space-y-6">
        <!-- Welcome Message -->
        <div class="text-center">
            <h2 class="text-2xl font-bold text-[#3a1d09] mb-2">Welcome Back</h2>
            <p class="text-[#f58502] font-medium">Sign in to your account</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="alert alert-info" :status="session('status')" />

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope mr-2"></i>Email Address
                </label>
                <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email address">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>
                <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#f58502] shadow-sm focus:ring-[#f58502] focus:ring-offset-0" name="remember">
                    <span class="ml-2 text-sm text-[#3a1d09]">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-[#f58502] hover:text-[#3a1d09] transition-colors duration-200" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn-primary w-full">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Sign In
            </button>
        </form>

        <!-- Social Login -->
        <div class="text-center">
            <p class="text-sm text-[#3a1d09] mb-4">Connect with us on social media</p>
            <div class="flex justify-center space-x-4">
                <a href="https://facebook.com/chariswordgospelministry" target="_blank" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition-colors duration-200">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://instagram.com/charisword" target="_blank" class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white hover:from-purple-600 hover:to-pink-600 transition-colors duration-200">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://twitter.com/ChariswordM" target="_blank" class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center text-white hover:bg-blue-500 transition-colors duration-200">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://tiktok.com/@charisword" target="_blank" class="w-10 h-10 bg-black rounded-full flex items-center justify-center text-white hover:bg-gray-800 transition-colors duration-200">
                    <i class="fab fa-tiktok"></i>
                </a>
            </div>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-center">
                <p class="text-sm text-[#3a1d09]">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-[#f58502] hover:text-[#3a1d09] font-semibold transition-colors duration-200">
                        Register here
                    </a>
                </p>
            </div>
        @endif
    </div>
</x-guest-layout>
