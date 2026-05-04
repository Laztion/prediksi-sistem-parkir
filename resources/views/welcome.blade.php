<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartPark AI — Sistem Reservasi Parkir Masa Depan</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles & Scripts -->
    <!-- Styles & Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    @vite(['resources/js/app.js'])
    
    <!-- Animation Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://unpkg.com/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://unpkg.com/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>

    <style>
        :root {
            --brand-primary: #6366f1;
            --brand-secondary: #a855f7;
            --bg-dark: #050505;
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            color: #e2e8f0;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.01);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .text-gradient {
            background: linear-gradient(135deg, #fff 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -10px var(--brand-primary);
        }

        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(168, 85, 247, 0.15));
            filter: blur(100px);
            border-radius: 50%;
            z-index: -1;
            animation: move 20s infinite alternate;
        }

        @keyframes move {
            from { transform: translate(-10%, -10%); }
            to { transform: translate(10%, 10%); }
        }

        .nav-link {
            position: relative;
            transition: color 0.3s;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--brand-primary);
            transition: width 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        [xl-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased overflow-x-hidden selection:bg-indigo-500/30">
    <!-- Background Elements -->
    <div class="blob top-[-100px] left-[-100px]"></div>
    <div class="blob bottom-[-100px] right-[-100px]" style="animation-delay: -5s;"></div>

    <!-- Sticky Header -->
    <header class="fixed top-0 left-0 right-0 z-[100] transition-all duration-500 border-b border-white/0" id="main-header">
        <nav class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3 group cursor-pointer">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center group-hover:rotate-12 transition-transform duration-500">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-white">Smart<span class="text-indigo-500">Park</span></span>
            </div>

            <div class="hidden md:flex items-center space-x-10">
                <a href="#ketersediaan" class="nav-link text-sm font-medium text-slate-300 hover:text-white transition">Ketersediaan</a>
                <a href="#fitur" class="nav-link text-sm font-medium text-slate-300 hover:text-white transition">Fitur Unggulan</a>
                <div class="h-4 w-px bg-white/10"></div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-bold text-white">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-bold text-white">Get Started</a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Hero Hero Section -->
    <section class="relative pt-32 pb-20 md:pt-48 md:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full glass-card border border-white/10 text-xs font-bold text-indigo-400 mb-8 animate__animated animate__fadeInDown">
                <span class="w-2 h-2 rounded-full bg-indigo-500 mr-3 animate-pulse"></span>
                NEW: Naive Bayes v2.0 Live Prediction
            </div>
            
            <h1 class="text-6xl md:text-8xl font-black tracking-tighter mb-8 leading-[1.1] animate__animated animate__fadeIn">
                <span class="text-gradient">Parkir Cerdas</span><br>
                <span class="text-indigo-500">Tanpa Batas.</span>
            </h1>
            
            <p class="max-w-2xl mx-auto text-lg md:text-xl text-slate-400 leading-relaxed mb-12 animate__animated animate__fadeIn animate__delay-1s">
                Lupakan membuang waktu mencari slot kosong. SmartPark menggunakan AI untuk memprediksi dan mencarikan tempat terbaik untuk kendaraan Anda secara real-time.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6 animate__animated animate__fadeInUp animate__delay-1s">
                <a href="#ketersediaan" class="btn-primary w-full sm:w-auto px-10 py-4 rounded-2xl text-white font-bold flex items-center justify-center group">
                    Lihat Ketersediaan
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
                <a href="#fitur" class="w-full sm:w-auto px-10 py-4 rounded-2xl glass-card text-white font-bold hover:bg-white/5 transition flex items-center justify-center">
                    Pelajari Fitur
                </a>
            </div>
        </div>
    </section>

    <!-- Main Live App Dashboard -->
    <section id="ketersediaan" class="py-20 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-16 text-center reveal">
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">Live Dashboard</h2>
                <div class="w-20 h-1.5 bg-indigo-600 mx-auto rounded-full"></div>
            </div>

            <div class="reveal">
                @livewire('parking-availability')
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-32 relative bg-white/[0.02]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="glass-card p-10 rounded-[2.5rem] group hover:scale-[1.02] transition-all duration-500 reveal">
                    <div class="w-16 h-16 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-400 mb-8 group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Akurasi AI</h3>
                    <p class="text-slate-400 leading-relaxed">Algoritma Naive Bayes kami memproses ribuan data histori untuk akurasi prediksi ketersediaan hingga 98%.</p>
                </div>

                <div class="glass-card p-10 rounded-[2.5rem] group hover:scale-[1.02] transition-all duration-500 reveal" style="transition-delay: 0.1s;">
                    <div class="w-16 h-16 bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-400 mb-8 group-hover:bg-purple-500 group-hover:text-white transition-colors duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Reservasi Aman</h3>
                    <p class="text-slate-400 leading-relaxed">Pesan slot parkir Anda sebelum sampai di lokasi. Sistem akan mengunci slot khusus untuk kendaraan Anda.</p>
                </div>

                <div class="glass-card p-10 rounded-[2.5rem] group hover:scale-[1.02] transition-all duration-500 reveal" style="transition-delay: 0.2s;">
                    <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-400 mb-8 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Update Real-time</h3>
                    <p class="text-slate-400 leading-relaxed">Visualisasi denah interaktif yang diperbarui secara langsung saat sensor mendeteksi perubahan status slot.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-20 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center space-y-8 md:space-y-0">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
                <span class="text-xl font-bold text-white">Smart<span class="text-indigo-500">Park</span></span>
            </div>
            
            <p class="text-slate-500 text-sm italic">&copy; 2026 SmartPark AI System. Project Managed by Antigravity Assistant.</p>
            
            <div class="flex space-x-6 text-sm text-slate-400 font-medium">
                <a href="#" class="hover:text-indigo-500 transition">Terms</a>
                <a href="#" class="hover:text-indigo-500 transition">Privacy</a>
                <a href="#" class="hover:text-indigo-500 transition">Contact</a>
            </div>
        </div>
    </footer>

    <!-- GSAP Initialization Scripts -->
    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Header Scroll Effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('main-header');
            if (window.scrollY > 50) {
                header.classList.add('glass-card', 'h-16');
                header.classList.remove('h-20');
            } else {
                header.classList.remove('glass-card', 'h-16');
                header.classList.add('h-20');
            }
        });

        // Reveal animations
        gsap.utils.toArray('.reveal').forEach((elem) => {
            gsap.from(elem, {
                scrollTrigger: {
                    trigger: elem,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                },
                y: 50,
                opacity: 0,
                duration: 1,
                ease: 'power3.out'
            });
        });
    </script>
</body>
</html>
