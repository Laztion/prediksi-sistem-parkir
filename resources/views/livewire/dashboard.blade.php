<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 animate__animated animate__fadeIn">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">Halo, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-slate-500 mt-1">Selamat datang di pusat kendali SmartPark AI Anda.</p>
        </div>
        <div class="flex space-x-3">
            @if(Auth::user()->hasRole('admin'))
            <a href="{{ route('admin.areas') }}" class="px-6 py-3 rounded-2xl text-sm font-bold text-white glass-card border border-white/10 hover:bg-white/5 flex items-center transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Manajemen Area
            </a>
            <a href="{{ route('admin.reservations') }}" class="px-6 py-3 rounded-2xl text-sm font-bold text-white glass-card border border-white/10 hover:bg-white/5 flex items-center transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Verifikasi Reservasi
            </a>
            @endif
            <a href="/" class="btn-primary px-6 py-3 rounded-2xl text-sm font-bold text-white shadow-lg shadow-indigo-500/20 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Booking Baru
            </a>
        </div>
    </div>

    <!-- Area Selection Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between px-2">
            <h2 class="text-xl font-extrabold text-white">Pilih Area Parkir</h2>
            <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-white/5 px-3 py-1 rounded-full border border-white/5">Update Otomatis</div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($areas as $area)
                <button wire:click="selectArea({{ $area->id }})" class="glass-card p-6 rounded-[2.5rem] text-left group hover:scale-[1.02] transition-all duration-500 relative overflow-hidden {{ $selectedAreaId == $area->id ? 'border-indigo-500/50 bg-indigo-500/5' : '' }}">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-start justify-between mb-4">
                            <div class="p-3 bg-white/5 rounded-2xl group-hover:bg-indigo-500/20 transition-colors">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span class="text-[10px] font-black {{ $area->available_count > 0 ? 'text-emerald-400' : 'text-red-400' }} uppercase tracking-tighter">{{ $area->available_count }} Slot Tersedia</span>
                        </div>
                        
                        <h3 class="text-lg font-black text-white leading-tight mb-1">{{ $area->name }}</h3>
                        <p class="text-xs text-slate-500 line-clamp-1">{{ $area->location }}</p>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Slot Selection (Visible when an area is selected) -->
    @if($showSlots)
    <div class="animate__animated animate__fadeInUp bg-slate-900/50 backdrop-blur-3xl border border-white/10 rounded-[3rem] p-10 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500"></div>
        
        <div class="flex items-center justify-between mb-10">
            <div>
                <h3 class="text-2xl font-black text-white tracking-tight">Pilih Slot di {{ \App\Models\ParkingArea::find($selectedAreaId)->name }}</h3>
                <p class="text-slate-500 text-sm mt-1">Klik pada slot yang tersedia (warna indigo) untuk melakukan reservasi.</p>
            </div>
            <button wire:click="$set('showSlots', false)" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-400 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        @if(session()->has('error'))
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-400 text-sm font-bold animate__animated animate__shakeX">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-4">
            @foreach($availableSlots as $slot)
                <button 
                    wire:click="reserveSlot({{ $slot->id }})"
                    @disabled($slot->status !== 'available')
                    class="aspect-square rounded-2xl flex flex-col items-center justify-center transition-all duration-300 group relative
                    {{ $slot->status === 'available' ? 'bg-indigo-500/10 border border-indigo-500/30 hover:bg-indigo-500/20 hover:scale-110' : 'bg-slate-800/50 border border-white/5 opacity-40 cursor-not-allowed' }}"
                >
                    <span class="text-xs font-black {{ $slot->status === 'available' ? 'text-indigo-400' : 'text-slate-600' }}">{{ $slot->slot_number }}</span>
                    @if($slot->type == 'motorcycle')
                        <svg class="w-3 h-3 mt-1 {{ $slot->status === 'available' ? 'text-indigo-500/50' : 'text-slate-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    @endif

                    @if($slot->status === 'available')
                        <div class="absolute inset-0 bg-indigo-500/10 rounded-2xl scale-0 group-hover:scale-100 transition-transform duration-300"></div>
                    @endif
                </button>
            @endforeach
        </div>

        <div class="mt-10 flex flex-wrap gap-6 items-center justify-center p-6 bg-white/5 rounded-[2rem] border border-white/5">
            <div class="flex items-center space-x-3">
                <div class="w-4 h-4 rounded-full bg-indigo-500/20 border border-indigo-500/50"></div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tersedia</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-4 h-4 rounded-full bg-slate-800/50 border border-white/5 opacity-40"></div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Terisi / Terpesan</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Admin Section -->
    @if(Auth::user()->hasRole('admin'))
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat Card 1 -->
        <div class="glass-card p-6 rounded-[2.5rem] relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-indigo-500/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4">Total Pengguna</p>
            <div class="flex items-end justify-between">
                <h3 class="text-4xl font-black text-white tracking-tighter">{{ $stats['total_users'] }}</h3>
                <div class="p-3 bg-indigo-500/10 rounded-2xl text-indigo-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="glass-card p-6 rounded-[2.5rem] relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-purple-500/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4">Area Parkir</p>
            <div class="flex items-end justify-between">
                <h3 class="text-4xl font-black text-white tracking-tighter">{{ $stats['total_areas'] }}</h3>
                <div class="p-3 bg-purple-500/10 rounded-2xl text-purple-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="glass-card p-6 rounded-[2.5rem] relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-500/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4">Total Slot</p>
            <div class="flex items-end justify-between">
                <h3 class="text-4xl font-black text-white tracking-tighter">{{ $stats['total_slots'] }}</h3>
                <div class="p-3 bg-emerald-500/10 rounded-2xl text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="glass-card p-6 rounded-[2.5rem] relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-500/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4">Aktif Booking</p>
            <div class="flex items-end justify-between">
                <h3 class="text-4xl font-black text-white tracking-tighter">{{ $stats['active_reservations'] }}</h3>
                <div class="p-3 bg-amber-500/10 rounded-2xl text-amber-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid lg:grid-cols-3 gap-10">
        <!-- Recent Reservations -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-xl font-extrabold text-white">Reservasi Saya</h2>
                <a href="#" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition">Lihat Semua</a>
            </div>

            @if(session()->has('message'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold animate__animated animate__fadeIn">
                    {{ session('message') }}
                </div>
            @endif

            <div class="glass-card rounded-[2.5rem] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-white/5">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Area</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Slot</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($myReservations as $reservation)
                                <tr class="group hover:bg-white/[0.01] transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="font-bold text-white">{{ $reservation->parkingSlot->parkingArea->name }}</div>
                                        <div class="text-[10px] text-slate-500 mt-0.5">{{ $reservation->parkingSlot->parkingArea->location }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="px-3 py-1 rounded-lg bg-indigo-500/10 text-indigo-400 font-black text-xs">{{ $reservation->parkingSlot->slot_number }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($reservation->status == 'active')
                                            <span class="inline-flex items-center text-emerald-400 text-xs font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> Aktif
                                            </span>
                                        @else
                                            <span class="text-slate-500 text-xs font-bold uppercase">{{ $reservation->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        @if($reservation->status == 'active')
                                            <button wire:click="cancelReservation({{ $reservation->id }})" class="text-xs font-black text-red-500/50 hover:text-red-500 transition uppercase tracking-tighter">Batalkan</button>
                                        @else
                                            <span class="text-[10px] text-slate-700 font-bold">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-slate-500 italic">Belum ada data reservasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Features -->
        <div class="space-y-8">
            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-600/10 blur-3xl rounded-full"></div>
                <h3 class="text-lg font-bold text-white mb-6">Informasi Akun</h3>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center text-white font-black text-xl">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-white">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-white/5 space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500 font-medium">Role</span>
                            <span class="text-indigo-400 font-bold uppercase tracking-widest">{{ Auth::user()->roles->pluck('name')->first() }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500 font-medium">Member Sejak</span>
                            <span class="text-white font-bold">{{ Auth::user()->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Tip or AI News -->
            <div class="glass-card p-8 rounded-[2.5rem] border-l-4 border-indigo-500">
                <h4 class="text-sm font-black text-indigo-400 uppercase tracking-widest mb-4">Tips SmartPark</h4>
                <p class="text-sm text-slate-400 leading-relaxed italic">
                    "Prediksi Naive Bayes menunjukkan akhir pekan pukul 14:00 - 18:00 adalah waktu tersibuk. Pesan 1 jam sebelumnya untuk menjamin ketersediaan!"
                </p>
            </div>
        </div>
    </div>
</div>
