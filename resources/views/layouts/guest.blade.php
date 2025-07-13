@php
    $bgImages = [
        'images/bg.png',
        'images/bg.jpg',
        'images/bgg.jpg',
        'images/bbg.jpg',
        'images/ben.jpg',
        'images/sh.jpeg',
        'images/ad.jpeg',
        'images/bb.jpeg',
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Charisword Gospel Ministry') }}</title>

        <!-- Montserrat Font -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <!-- Font Awesome for enhanced icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .auth-hero-bg {
                /* background-image: url('{{ asset("images/bg.jpg") }}'); */
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                position: relative;
            }
            
            /* Add overlay for better text readability */
            .auth-hero-bg::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(245, 133, 2, 0.8), rgba(255, 154, 46, 0.6));
                z-index: 1;
            }
            
            /* Ensure content appears above overlay */
            .auth-hero-bg > * {
                position: relative;
                z-index: 2;
            }
            
            /* Alternative: If you want to use a placeholder image */
            .auth-hero-bg-placeholder {
                background-image: url('/placeholder.svg?height=800&width=1200');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
        </style>
    </head>
    <body class="min-h-screen">
        <div class="auth-flex">
            <!-- Auth Form Section -->
            <div class="auth-card-wrap">
                <div class="auth-card">
                    <!-- Logo and Welcome -->
                    <div class="text-center mb-8">
                        <div class="relative inline-block">
                            <img src="{{ asset('images/logoo.png') }}" alt="Church Logo" class="auth-logo animate-float" />
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-r from-[#f58502] to-[#ff9a2e] rounded-full flex items-center justify-center animate-pulse-slow">
                                <i class="fas fa-cross text-white text-sm"></i>
                            </div>
                        </div>
                        <h1 class="auth-welcome">Welcome to Charisword Gospel Ministry</h1>
                        <p class="auth-tagline">Raising Able Ministers of Grace World Wide</p>
                    </div>

                    <!-- Auth Content -->
                    {{ $slot }}

                  
                </div>
            </div>

            <!-- Hero Section with Background Image -->
            <div class="auth-hero-bg" id="hero-bg">
                <div class="relative h-full flex items-center justify-center">
                    <div class="text-center text-white z-10">
                        <h2 class="text-4xl lg:text-6xl font-bold mb-6 drop-shadow-lg">
                            Raising Able Ministers of Grace
                        </h2>
                        <p class="text-xl lg:text-2xl mb-8 opacity-90 drop-shadow">
                            Transforming Lives Through Christ
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-6">
                                <i class="fas fa-pray text-3xl mb-4"></i>
                                <h3 class="text-lg font-bold mb-2">Worship</h3>
                                <p class="text-sm opacity-90">Experience powerful worship services</p>
                            </div>
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-6">
                                <i class="fas fa-hands-helping text-3xl mb-4"></i>
                                <h3 class="text-lg font-bold mb-2">Community</h3>
                                <p class="text-sm opacity-90">Join our loving church family</p>
                            </div>
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-6">
                                <i class="fas fa-heart text-3xl mb-4"></i>
                                <h3 class="text-lg font-bold mb-2">Growth</h3>
                                <p class="text-sm opacity-90">Grow in faith and purpose</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Footer -->
        <div class="auth-footer">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Contact Information -->
                    <div class="footer-section">
                        <h3 class="text-[#f58502] font-bold mb-3">
                            <i class="fas fa-phone-alt mr-2"></i>CONNECT WITH US
                        </h3>
                        <div class="space-y-2 text-sm">
                            <p><i class="fas fa-phone mr-2"></i>Call or Message: <a href="tel:0261169859" class="text-[#f58502] hover:underline">026 116 9859</a></p>
                            <p><i class="fas fa-map-marker-alt mr-2"></i>Location: Lashibi, Transformer Junction</p>
                            <p class="text-xs opacity-70 ml-6">(Sakumono to Ashaiman Highway)</p>
                            <p><i class="fas fa-map mr-2"></i>Digital Address: GT-337-6599</p>
                            <p><i class="fas fa-search mr-2"></i>Google Maps: Search "Charisword Gospel Ministry"</p>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="footer-section">
                        <h3 class="text-[#f58502] font-bold mb-3">
                            <i class="fas fa-share-alt mr-2"></i>SOCIAL MEDIA
                        </h3>
                        <div class="space-y-2 text-sm">
                            <p><i class="fab fa-facebook mr-2"></i><a href="https://facebook.com/chariswordgospelministry" target="_blank" class="text-[#f5852] hover:underline">Charisword Gospel Ministry</a></p>
                            <p><i class="fab fa-instagram mr-2"></i><a href="https://instagram.com/charisword" target="_blank" class="text-[#f58502] hover:underline">@charisword</a></p>
                            <p><i class="fab fa-tiktok mr-2"></i><a href="https://tiktok.com/@charisword" target="_blank" class="text-[#f58502] hover:underline">@charisword</a></p>
                            <p><i class="fab fa-twitter mr-2"></i><a href="https://twitter.com/ChariswordM" target="_blank" class="text-[#f58502] hover:underline">@ChariswordM</a></p>
                        </div>
                    </div>

                    <!-- Giving & Support -->
                    <div class="footer-section">
                        <h3 class="text-[#f58502] font-bold mb-3">
                            <i class="fas fa-heart mr-2"></i>GIVING & SUPPORT
                        </h3>
                        <div class="space-y-2 text-sm">
                            <p><i class="fas fa-mobile-alt mr-2"></i>MTN MOMO: <span class="font-bold">0248645966</span></p>
                            <p><i class="fas fa-credit-card mr-2"></i>TELECEL CASH: <span class="font-bold">364097</span></p>
                            <p><i class="fas fa-university mr-2"></i>FIDELITY BANK: <span class="font-bold">1050052233116</span></p>
                            <p><i class="fas fa-university mr-2"></i>STANBIC BANK: <span class="font-bold">9040011571950</span></p>
                        </div>
                    </div>

                    <!-- Contact & Email -->
                    <div class="footer-section">
                        <h3 class="text-[#f58502] font-bold mb-3">
                            <i class="fas fa-envelope mr-2"></i>CONTACT US
                        </h3>
                        <div class="space-y-2 text-sm">
                            <p><i class="fas fa-envelope mr-2"></i><a href="mailto:chariswordgh@gmail.com" class="text-[#f58502] hover:underline">chariswordgh@gmail.com</a></p>
                            <p><i class="fas fa-globe mr-2"></i>Website: <a href="#" class="text-[#f58502] hover:underline">charisword.org</a></p>
                            <p><i class="fas fa-pray mr-2"></i>Prayer Requests</p>
                            <p><i class="fas fa-hands-helping mr-2"></i>Volunteer Opportunities</p>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="mt-8 pt-6 border-t border-[#3a1d09] border-opacity-20 text-center">
                    <p class="text-sm opacity-80">
                        © {{ date('Y') }} Charisword Gospel Ministry. All rights reserved. 
                        <span class="text-[#f58502] font-semibold">Raising Able Ministers of Grace</span>
                    </p>
                </div>
            </div>
        </div>
        <script>
    const heroImages = @json($bgImages);
    let heroIndex = Math.floor(Math.random() * heroImages.length);
    const heroBg = document.getElementById('hero-bg');
    function setHeroBg(idx) {
        heroBg.style.backgroundImage = `url('/${heroImages[idx]}')`;
    }
    document.addEventListener('DOMContentLoaded', function() {
        setHeroBg(heroIndex);
        setInterval(() => {
            heroIndex = (heroIndex + 1) % heroImages.length;
            setHeroBg(heroIndex);
        }, 6000); // Change every 6 seconds
    });
</script>
    </body>
</html>