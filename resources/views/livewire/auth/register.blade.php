<div class="min-h-screen flex items-center justify-center relative overflow-hidden px-4 py-20">
    <!-- Background Blobs -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-500/10 blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-purple-500/10 blur-[120px]"></div>

    <div class="w-full max-w-md animate__animated animate__zoomIn">
        <div class="text-center mb-10">
            <a href="/" class="inline-flex items-center space-x-3 group mb-6">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center group-hover:rotate-12 transition-transform duration-500">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
                <span class="text-3xl font-black tracking-tighter text-white">Smart<span class="text-indigo-500">Park</span></span>
            </a>
            <h2 class="text-2xl font-bold text-white tracking-tight">Buat Akun Baru</h2>
            <p class="text-slate-400 mt-2">Mulai perjalanan parkir cerdas Anda hari ini.</p>
        </div>

        <div class="glass-card p-10 rounded-[2.5rem]">
            <form wire:submit="register" class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Nama Lengkap</label>
                    <input wire:model="name" type="text" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="John Doe">
                    @error('name') <span class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Email Address</label>
                    <input wire:model="email" type="email" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="name@example.com">
                    @error('email') <span class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Password</label>
                    <input wire:model="password" type="password" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="••••••••">
                    @error('password') <span class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Konfirmasi Password</label>
                    <input wire:model="password_confirmation" type="password" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="••••••••">
                </div>

                <button type="submit" class="w-full btn-primary py-4 rounded-2xl text-white font-bold text-lg shadow-xl shadow-indigo-500/20 group relative overflow-hidden">
                    <span wire:loading.remove>Sign Up</span>
                    <span wire:loading class="flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 mt-8 text-sm">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-indigo-400 font-bold hover:text-indigo-300 transition">Masuk di sini</a>
        </p>
    </div>
</div>
