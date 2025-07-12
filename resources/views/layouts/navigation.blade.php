<nav class="bg-white shadow font-montserrat border-b border-[#f58502]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-4">
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('images/logoo.png') }}" alt="Church Logo" class="h-10 w-10 object-contain rounded-full border-2 border-[#f58502]" />
                    <span class="text-xl font-bold text-[#3a1d09] tracking-wide">Charisword Gospel Ministry</span>
                </a>
            </div>
            <div class="hidden md:flex gap-6 items-center">
                <!-- Example nav links, update as needed -->
                <a href="{{ route('dashboard') }}" class="text-[#3a1d09] px-3 py-2 rounded hover:bg-orange-50 transition-all duration-150 @if(request()->routeIs('dashboard')) border-b-4 border-[#f58502] font-bold @endif">Dashboard</a>
                <a href="{{ route('members.index') }}" class="text-[#3a1d09] px-3 py-2 rounded hover:bg-orange-50 transition-all duration-150 @if(request()->routeIs('members.*')) border-b-4 border-[#f58502] font-bold @endif">Members</a>
                <a href="{{ route('folds.index') }}" class="text-[#3a1d09] px-3 py-2 rounded hover:bg-orange-50 transition-all duration-150 @if(request()->routeIs('folds.*')) border-b-4 border-[#f58502] font-bold @endif">Folds</a>
                <a href="{{ route('first_timers.index') }}" class="text-[#3a1d09] px-3 py-2 rounded hover:bg-orange-50 transition-all duration-150 @if(request()->routeIs('first_timers.*')) border-b-4 border-[#f58502] font-bold @endif">First Timers</a>
                <a href="{{ route('services.index') }}" class="text-[#3a1d09] px-3 py-2 rounded hover:bg-orange-50 transition-all duration-150 @if(request()->routeIs('services.*')) border-b-4 border-[#f58502] font-bold @endif">Services</a>
                <!-- Add more links as needed -->
            </div>
            <div class="md:hidden flex items-center">
                <!-- Mobile menu button -->
                <button id="mobile-menu-button" class="text-[#3a1d09] focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
        <!-- Mobile menu, hidden by default -->
        <div id="mobile-menu" class="md:hidden hidden flex-col gap-2 mt-2">
            <a href="{{ route('dashboard') }}" class="block text-[#3a1d09] px-3 py-2 rounded hover:bg-orange-50 @if(request()->routeIs('dashboard')) border-l-4 border-[#f58502] font-bold @endif">Dashboard</a>
            <a href="{{ route('members.index') }}" class="block text-[#3a1d09] px-3 py-2 rounded hover:bg-orange-50 @if(request()->routeIs('members.*')) border-l-4 border-[#f58502] font-bold @endif">Members</a>
            <a href="{{ route('folds.index') }}" class="block text-[#3a1d09] px-3 py-2 rounded hover:bg-orange-50 @if(request()->routeIs('folds.*')) border-l-4 border-[#f58502] font-bold @endif">Folds</a>
            <a href="{{ route('first_timers.index') }}" class="block text-[#3a1d09] px-3 py-2 rounded hover:bg-orange-50 @if(request()->routeIs('first_timers.*')) border-l-4 border-[#f58502] font-bold @endif">First Timers</a>
            <a href="{{ route('services.index') }}" class="block text-[#3a1d09] px-3 py-2 rounded hover:bg-orange-50 @if(request()->routeIs('services.*')) border-l-4 border-[#f58502] font-bold @endif">Services</a>
            <!-- Add more links as needed -->
        </div>
    </div>
    <script>
        // Simple mobile menu toggle
        document.getElementById('mobile-menu-button').onclick = function() {
            var menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        };
    </script>
</nav>
