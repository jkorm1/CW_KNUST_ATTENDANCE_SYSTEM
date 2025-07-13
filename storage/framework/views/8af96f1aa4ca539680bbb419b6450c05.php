<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Charisword Gospel Ministry')); ?></title>

        <!-- Montserrat Font -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <!-- Font Awesome for enhanced icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="min-h-screen flex flex-col">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <aside class="sidebar w-72 p-8 flex flex-col gap-8 items-center justify-between hidden lg:flex">
                <div class="flex flex-col gap-8 w-full">
                    <!-- Logo Section -->
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative">
                            <img src="<?php echo e(asset('images/logoo.png')); ?>" alt="Church Logo" class="w-24 h-24 object-contain rounded-full border-4 border-[#f58502] bg-white shadow-lg" />
                        </div>
                        <div class="text-center">
                            <h1 class="text-xl font-bold tracking-wide text-white">Charisword Gospel Ministry</h1>
                            <p class="text-sm text-orange-200 mt-1">Raising Able Ministers of Grace</p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="sidebar-nav flex flex-col gap-3 mt-8 w-full">
                        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-4 py-3 px-4 transition-all duration-200 <?php if(request()->routeIs('dashboard')): ?> active-glow <?php endif; ?>">
                            <i class="fas fa-home text-lg"></i>
                            <span class="font-semibold">Dashboard</span>
                        </a>
                        <a href="<?php echo e(route('members.index')); ?>" class="flex items-center gap-4 py-3 px-4 transition-all duration-200 <?php if(request()->routeIs('members.*')): ?> active-glow <?php endif; ?>">
                            <i class="fas fa-users text-lg"></i>
                            <span class="font-semibold">Members</span>
                        </a>
                        <a href="<?php echo e(route('folds.index')); ?>" class="flex items-center gap-4 py-3 px-4 transition-all duration-200 <?php if(request()->routeIs('folds.*')): ?> active-glow <?php endif; ?>">
                            <i class="fas fa-layer-group text-lg"></i>
                            <span class="font-semibold">Folds</span>
                        </a>
                        <a href="<?php echo e(route('first_timers.index')); ?>" class="flex items-center gap-4 py-3 px-4 transition-all duration-200 <?php if(request()->routeIs('first_timers.*')): ?> active-glow <?php endif; ?>">
                            <i class="fas fa-user-plus text-lg"></i>
                            <span class="font-semibold">First Timers</span>
                        </a>
                        <a href="<?php echo e(route('services.index')); ?>" class="flex items-center gap-4 py-3 px-4 transition-all duration-200 <?php if(request()->routeIs('services.*')): ?> active-glow <?php endif; ?>">
                            <i class="fas fa-calendar-alt text-lg"></i>
                            <span class="font-semibold">Services</span>
                        </a>
                        <a href="<?php echo e(route('attendance.report')); ?>" class="flex items-center gap-4 py-3 px-4 transition-all duration-200 <?php if(request()->routeIs('attendance.report')): ?> active-glow <?php endif; ?>">
                            <i class="fas fa-chart-bar text-lg"></i>
                            <span class="font-semibold">Report</span>
                        </a>
                        <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-4 py-3 px-4 transition-all duration-200 <?php if(request()->routeIs('profile.edit')): ?> active-glow <?php endif; ?>">
                            <i class="fas fa-user-circle text-lg"></i>
                            <span class="font-semibold">Profile</span>
                        </a>
                        
                        <!-- Logout Button -->
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full mt-4">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="flex items-center gap-4 py-3 px-4 w-full text-left transition-all duration-200 hover:bg-red-600 hover:text-white rounded-lg">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                                <span class="font-semibold">Logout</span>
                            </button>
                        </form>
                    </nav>
                </div>

                <!-- User Profile Section -->
                <div class="flex flex-col items-center gap-3">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(auth()->user()->name ?? 'User')); ?>&background=f58502&color=fff&size=128&font-size=0.4" alt="Profile" class="w-16 h-16 rounded-full border-3 border-[#f58502] shadow-lg" />
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="text-center">
                        <span class="text-sm font-semibold text-white"><?php echo e(auth()->user()->name ?? 'User'); ?></span>
                        <p class="text-xs text-orange-200">Administrator</p>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col min-h-screen main-content lg:ml-72">
                <!-- Page Content -->
                <div class="flex-1 p-6 lg:p-12">
                    <div class="glass-card p-8 lg:p-12 mb-8">
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>

                <!-- Footer -->
                <footer class="footer-modern">
                    <div class="footer-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            <!-- Contact Information -->
                            <div class="footer-section">
                                <h3><i class="fas fa-phone-alt mr-2"></i>CONNECT WITH US</h3>
                                <div class="space-y-2 text-sm">
                                    <p><i class="fas fa-phone mr-2"></i>Call or Message: <a href="tel:0261169859" class="hover:text-[#f58502] transition-colors">026 116 9859</a></p>
                                    <p><i class="fas fa-map-marker-alt mr-2"></i>Location: Lashibi, Transformer Junction</p>
                                    <p class="text-xs opacity-80 ml-6">(Sakumono to Ashaiman Highway)</p>
                                    <p><i class="fas fa-map mr-2"></i>Digital Address: GT-337-6599</p>
                                    <p><i class="fas fa-search mr-2"></i>Google Maps: Search "Charisword Gospel Ministry"</p>
                                </div>
                            </div>

                            <!-- Social Media -->
                            <div class="footer-section">
                                <h3><i class="fas fa-share-alt mr-2"></i>SOCIAL MEDIA</h3>
                                <div class="space-y-2 text-sm">
                                    <p><i class="fab fa-facebook mr-2"></i><a href="https://facebook.com/chariswordgospelministry" target="_blank" class="hover:text-[#f58502] transition-colors">Charisword Gospel Ministry</a></p>
                                    <p><i class="fab fa-instagram mr-2"></i><a href="https://instagram.com/charisword" target="_blank" class="hover:text-[#f58502] transition-colors">@charisword</a></p>
                                    <p><i class="fab fa-tiktok mr-2"></i><a href="https://tiktok.com/@charisword" target="_blank" class="hover:text-[#f58502] transition-colors">@charisword</a></p>
                                    <p><i class="fab fa-twitter mr-2"></i><a href="https://twitter.com/ChariswordM" target="_blank" class="hover:text-[#f58502] transition-colors">@ChariswordM</a></p>
                                </div>
                            </div>

                            <!-- Giving & Support -->
                            <div class="footer-section">
                                <h3><i class="fas fa-heart mr-2"></i>GIVING & SUPPORT</h3>
                                <div class="space-y-2 text-sm">
                                    <p><i class="fas fa-mobile-alt mr-2"></i>MTN MOMO: <span class="font-bold">0248645966</span></p>
                                    <p><i class="fas fa-credit-card mr-2"></i>TELECEL CASH: <span class="font-bold">364097</span></p>
                                    <p><i class="fas fa-university mr-2"></i>FIDELITY BANK: <span class="font-bold">1050052233116</span></p>
                                    <p><i class="fas fa-university mr-2"></i>STANBIC BANK: <span class="font-bold">9040011571950</span></p>
                                </div>
                            </div>

                            <!-- Contact & Email -->
                            <div class="footer-section">
                                <h3><i class="fas fa-envelope mr-2"></i>CONTACT US</h3>
                                <div class="space-y-2 text-sm">
                                    <p><i class="fas fa-envelope mr-2"></i><a href="mailto:chariswordgh@gmail.com" class="hover:text-[#f58502] transition-colors">chariswordgh@gmail.com</a></p>
                                    <p><i class="fas fa-globe mr-2"></i>Website: <a href="#" class="hover:text-[#f58502] transition-colors">charisword.org</a></p>
                                    <p><i class="fas fa-pray mr-2"></i>Prayer Requests</p>
                                    <p><i class="fas fa-hands-helping mr-2"></i>Volunteer Opportunities</p>
                                </div>
                            </div>
                        </div>

                        <!-- Copyright -->
                        <div class="mt-8 pt-6 border-t border-white border-opacity-20 text-center">
                            <p class="text-sm opacity-80">
                                © <?php echo e(date('Y')); ?> Charisword Gospel Ministry. All rights reserved. 
                                <span class="text-[#f58502] font-semibold">Raising Able Ministers of Grace</span>
                            </p>
                        </div>
                    </div>
                </footer>
            </main>
        </div>

        <!-- Mobile Navigation Toggle -->
        <div class="lg:hidden fixed bottom-6 right-6 z-50">
            <button id="mobile-nav-toggle" class="bg-[#f58502] text-white p-4 rounded-full shadow-lg hover:bg-[#e67600] transition-all duration-200">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-nav" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden">
            <div class="absolute right-0 top-0 h-full w-80 bg-[#3a1d09] p-6 transform translate-x-full transition-transform duration-300">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-white text-xl font-bold">Menu</h2>
                    <button id="mobile-nav-close" class="text-white text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <nav class="space-y-4">
                    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-4 py-3 px-4 text-white hover:bg-[#f58502] rounded-lg transition-all duration-200">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="<?php echo e(route('members.index')); ?>" class="flex items-center gap-4 py-3 px-4 text-white hover:bg-[#f58502] rounded-lg transition-all duration-200">
                        <i class="fas fa-users"></i>
                        <span>Members</span>
                    </a>
                    <a href="<?php echo e(route('folds.index')); ?>" class="flex items-center gap-4 py-3 px-4 text-white hover:bg-[#f58502] rounded-lg transition-all duration-200">
                        <i class="fas fa-layer-group"></i>
                        <span>Folds</span>
                    </a>
                    <a href="<?php echo e(route('first_timers.index')); ?>" class="flex items-center gap-4 py-3 px-4 text-white hover:bg-[#f58502] rounded-lg transition-all duration-200">
                        <i class="fas fa-user-plus"></i>
                        <span>First Timers</span>
                    </a>
                    <a href="<?php echo e(route('services.index')); ?>" class="flex items-center gap-4 py-3 px-4 text-white hover:bg-[#f58502] rounded-lg transition-all duration-200">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Services</span>
                    </a>
                    <a href="<?php echo e(route('attendance.report')); ?>" class="flex items-center gap-4 py-3 px-4 text-white hover:bg-[#f58502] rounded-lg transition-all duration-200">
                        <i class="fas fa-chart-bar"></i>
                        <span>Report</span>
                    </a>
                    <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-4 py-3 px-4 text-white hover:bg-[#f58502] rounded-lg transition-all duration-200">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
                    </a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="flex items-center gap-4 py-3 px-4 w-full text-left text-white hover:bg-red-600 rounded-lg transition-all duration-200">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <?php echo $__env->yieldPushContent('scripts'); ?>
        
        <script>
            // Mobile Navigation Toggle
            document.getElementById('mobile-nav-toggle').addEventListener('click', function() {
                document.getElementById('mobile-nav').classList.remove('hidden');
                setTimeout(() => {
                    document.querySelector('#mobile-nav > div').classList.remove('translate-x-full');
                }, 10);
            });

            document.getElementById('mobile-nav-close').addEventListener('click', function() {
                document.querySelector('#mobile-nav > div').classList.add('translate-x-full');
                setTimeout(() => {
                    document.getElementById('mobile-nav').classList.add('hidden');
                }, 300);
            });

            // Close mobile nav when clicking outside
            document.getElementById('mobile-nav').addEventListener('click', function(e) {
                if (e.target === this) {
                    document.querySelector('#mobile-nav > div').classList.add('translate-x-full');
                    setTimeout(() => {
                        document.getElementById('mobile-nav').classList.add('hidden');
                    }, 300);
                }
            });
        </script>
    </body>
</html><?php /**PATH /var/www/html/resources/views/layouts/app.blade.php ENDPATH**/ ?>