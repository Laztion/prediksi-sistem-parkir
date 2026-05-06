<div class="space-y-16" wire:poll.5s x-data="{ showReservationModal: @entangle('showReservationModal').live }">
    @if (session()->has('message'))
        <div class="fixed bottom-10 right-10 z-[200] glass-card px-8 py-5 rounded-[2rem] border-l-8 border-indigo-600 text-white shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] animate__animated animate__fadeInRight">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="font-bold text-lg">Berhasil!</p>
                    <p class="text-sm text-slate-400">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Selection & Prediction Area -->
    <!-- Hero Prediction Section -->
    <div class="relative mb-20">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/20 to-purple-600/20 blur-[120px] rounded-[5rem]"></div>
        <div class="relative glass-card p-12 rounded-[4rem] border border-white/10 overflow-hidden shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)]">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-10">
                    <div class="inline-flex items-center space-x-3 px-5 py-2 bg-indigo-600/10 border border-indigo-500/20 rounded-full">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 animate-ping"></div>
                        <span class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em]">AI Prediction Engine Active</span>
                    </div>

                    <div class="space-y-4">
                        <h1 class="text-6xl font-black text-white leading-none tracking-tighter">
                            Cek Parkir <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Masa Depan</span>
                        </h1>
                        <p class="text-slate-400 text-lg max-w-md leading-relaxed">
                            Gunakan algoritma <span class="text-white font-bold">Naive Bayes</span> untuk memprediksi ketersediaan slot parkir berdasarkan data histori.
                        </p>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Pilih Hari</label>
                            <select wire:model.live="predictionDay" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 transition-all outline-none appearance-none cursor-pointer hover:bg-white/[0.05]">
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                    <option value="{{ $day }}" class="bg-slate-900">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Pilih Jam</label>
                            <select wire:model.live="predictionTime" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 transition-all outline-none appearance-none cursor-pointer hover:bg-white/[0.05]">
                                @for($i = 0; $i < 24; $i++)
                                    @php $time = sprintf('%02d:00', $i); @endphp
                                    <option value="{{ $time }}" class="bg-slate-900">{{ $time }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <!-- Probability Ring/Card -->
                    <div class="relative glass-card p-10 rounded-[3.5rem] border border-white/10 bg-white/[0.01] flex flex-col items-center justify-center text-center group">
                        <div class="absolute inset-0 bg-indigo-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        
                        <div class="relative w-48 h-48 mb-8">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8" class="text-white/[0.05]" />
                                <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8" 
                                    stroke-dasharray="{{ 2 * pi() * 45 }}" 
                                    stroke-dashoffset="{{ (1 - $probability/100) * (2 * pi() * 45) }}"
                                    class="{{ $prediction === 'available' ? 'text-indigo-500' : 'text-rose-500' }} transition-all duration-1000 cubic-bezier(0.4, 0, 0.2, 1)" 
                                    stroke-linecap="round" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-4xl font-black text-white">{{ $probability }}%</span>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Confidence</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em]">Hasil Prediksi</p>
                            <h2 class="text-5xl font-black {{ $prediction === 'available' ? 'text-white' : 'text-rose-500' }} tracking-tighter">
                                {{ $prediction === 'available' ? 'TERSEDIA' : 'PENUH' }}
                            </h2>
                            <p class="text-sm text-slate-400 font-medium">
                                Untuk {{ $predictionDay }}, Pukul {{ $predictionTime }}
                            </p>
                        </div>

                        @if($isFuture)
                        <div class="mt-8 px-6 py-3 bg-amber-500/10 border border-amber-500/20 rounded-2xl">
                            <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest flex items-center">
                                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Prediksi Masa Depan
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline Forecast -->
            <div class="mt-16 pt-12 border-t border-white/10">
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h4 class="text-xl font-black text-white tracking-tight">Prediksi Harian</h4>
                        <p class="text-xs text-slate-500 mt-1 uppercase tracking-widest font-bold">Probabilitas ketersediaan 24 jam</p>
                    </div>
                    <div class="flex space-x-4 text-[10px] font-black uppercase tracking-widest">
                        <div class="flex items-center text-rose-500"><span class="w-2 h-2 bg-rose-500 rounded-full mr-2"></span> Penuh</div>
                        <div class="flex items-center text-indigo-500"><span class="w-2 h-2 bg-indigo-500 rounded-full mr-2"></span> Tersedia</div>
                    </div>
                </div>

                <div class="flex items-end justify-between gap-1 h-32">
                    @foreach($dailyTimeline as $time => $prob)
                        <div class="flex-1 group relative h-full flex flex-col justify-end">
                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-4 opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-20">
                                <div class="glass-card px-4 py-2 rounded-xl border border-white/10 shadow-2xl whitespace-nowrap">
                                    <p class="text-[10px] font-black text-white">{{ $time }}</p>
                                    <p class="text-[14px] font-black {{ $prob > 50 ? 'text-indigo-400' : 'text-rose-400' }}">{{ $prob }}%</p>
                                </div>
                                <div class="w-2 h-2 bg-slate-800 border-r border-b border-white/10 rotate-45 mx-auto -mt-1"></div>
                            </div>
                            
                            <!-- Bar -->
                            <div 
                                class="w-full rounded-t-lg transition-all duration-500 {{ $time === $predictionTime ? 'ring-2 ring-white ring-offset-4 ring-offset-slate-900 z-10 scale-x-110' : 'opacity-40 group-hover:opacity-100' }} {{ $prob > 50 ? 'bg-gradient-to-t from-indigo-600 to-indigo-400' : 'bg-gradient-to-t from-rose-600 to-rose-400' }}"
                                style="height: {{ max($prob, 5) }}%"
                            ></div>
                            
                            <!-- Label -->
                            @if($loop->index % 4 === 0)
                                <div class="absolute top-full mt-4 left-1/2 -translate-x-1/2 text-[9px] font-black text-slate-600 uppercase tracking-tighter">{{ $time }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid lg:grid-cols-4 gap-10 items-start">
        <div class="lg:col-span-1 space-y-8 sticky top-24">
            <div class="glass-card p-8 rounded-[2.5rem] space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Lokasi Parkir</label>
                    <div class="relative group">
                        <select wire:model.live="selectedAreaId" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-semibold focus:ring-2 focus:ring-indigo-500 transition-all outline-none appearance-none cursor-pointer group-hover:bg-white/[0.05]">
                            @foreach($areas as $item)
                                <option value="{{ $item->id }}" class="bg-slate-900">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                @if($area)
                <div class="space-y-4 pt-6 border-t border-white/5">
                    @if($area->google_maps_link)
                    <a href="{{ $area->google_maps_link }}" target="_blank" class="w-full py-4 glass-card border border-white/10 rounded-2xl text-xs font-black text-indigo-400 hover:bg-indigo-500/10 flex items-center justify-center transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        BUKA NAVIGASI MAPS
                    </a>
                    @endif

                    @if($area->map_image)
                    <div x-data="{ open: false }">
                        <button @click="open = true" class="w-full py-4 glass-card border border-white/10 rounded-2xl text-xs font-black text-slate-400 hover:text-white hover:bg-white/5 flex items-center justify-center transition-all uppercase tracking-widest">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                            LIHAT DENAH PARKIR
                        </button>
                        
                        <div x-show="open" class="fixed inset-0 z-[300] flex items-center justify-center p-4 bg-black/90 backdrop-blur-md" @click.away="open = false" style="display: none;">
                            <div class="relative max-w-5xl w-full">
                                <button @click="open = false" class="absolute -top-12 right-0 text-white hover:text-slate-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <img src="{{ asset('storage/' . $area->map_image) }}" alt="Map Area" class="w-full rounded-3xl shadow-2xl">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Export Button -->
            <button wire:click="exportToExcel" class="w-full py-4 glass-card border border-white/10 rounded-2xl text-[10px] font-black text-indigo-400 hover:bg-indigo-600 hover:text-white flex items-center justify-center transition-all uppercase tracking-widest shadow-lg shadow-indigo-500/10">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Reservasi (Excel)
            </button>
        </div>

        <!-- Slot Grid Area -->
        <div class="lg:col-span-3 glass-card p-10 rounded-[3rem]">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
                <div>
                    <h3 class="text-2xl font-extrabold text-white tracking-tight">Ketersediaan Real-time</h3>
                    <p class="text-sm text-slate-500 mt-1">Interaktif denah parkir saat ini.</p>
                </div>
                <div class="flex bg-white/[0.03] p-1.5 rounded-2xl border border-white/5 space-x-2">
                    <div class="flex items-center px-3 py-1.5 rounded-xl bg-white/[0.05]"><span class="w-2 h-2 bg-indigo-500 rounded-full mr-2"></span> <span class="text-[10px] font-bold text-slate-300">Available</span></div>
                    <div class="flex items-center px-3 py-1.5 rounded-xl"><span class="w-2 h-2 bg-slate-700 rounded-full mr-2"></span> <span class="text-[10px] font-bold text-slate-500">Full</span></div>
                    <div class="flex items-center px-3 py-1.5 rounded-xl"><span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span> <span class="text-[10px] font-bold text-slate-500">Reserved</span></div>
                </div>
            </div>

            <div class="space-y-12">
                @foreach($sectors as $sector)
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3">
                            <h4 class="text-lg font-black text-white tracking-tight">{{ $sector->name }}</h4>
                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-lg">{{ $sector->code }}</span>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-6">
                            @foreach($sector->parkingSlots as $slot)
                                <button 
                                    wire:click="openReserveModal({{ $slot->id }})"
                                    @if($slot->status !== 'available') disabled @endif
                                    class="group relative aspect-square rounded-[2rem] transition-all duration-500 transform active:scale-95 flex flex-col items-center justify-center border
                                    {{ $slot->status === 'available' ? 'bg-white/[0.02] hover:bg-indigo-600/20 border-white/5 hover:border-indigo-500/50' : ($slot->status === 'reserved' ? 'bg-amber-500/10 border-amber-500/20 cursor-not-allowed' : 'bg-white/[0.01] border-white/0 opacity-30 cursor-not-allowed') }}"
                                >
                                    <span class="text-xs font-black tracking-widest {{ $slot->status === 'available' ? 'text-slate-400 group-hover:text-white' : 'text-slate-600' }}">{{ $slot->slot_number }}</span>
                                    
                                    <div class="mt-3 relative">
                                        @if($slot->status === 'available')
                                            <div class="absolute inset-0 bg-indigo-500 blur-lg opacity-0 group-hover:opacity-40 transition-opacity"></div>
                                            <svg class="w-7 h-7 text-indigo-500 relative group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        @elseif($slot->status === 'reserved')
                                            <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                        @else
                                            <svg class="w-6 h-6 text-slate-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                        @endif
                                    </div>
                                    
                                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-8 h-1 rounded-full {{ $slot->status === 'available' ? 'bg-indigo-600 opacity-0 group-hover:opacity-100' : '' }} transition-all"></div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                @if($unassignedSlots->count() > 0)
                    <div class="space-y-6 pt-8 border-t border-white/5">
                        <h4 class="text-lg font-black text-slate-500 tracking-tight">Slot Tanpa Sektor</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-6">
                            @foreach($unassignedSlots as $slot)
                                <button 
                                    wire:click="openReserveModal({{ $slot->id }})"
                                    @if($slot->status !== 'available') disabled @endif
                                    class="group relative aspect-square rounded-[2rem] transition-all duration-500 transform active:scale-95 flex flex-col items-center justify-center border
                                    {{ $slot->status === 'available' ? 'bg-white/[0.02] hover:bg-indigo-600/20 border-white/5 hover:border-indigo-500/50' : ($slot->status === 'reserved' ? 'bg-amber-500/10 border-amber-500/20 cursor-not-allowed' : 'bg-white/[0.01] border-white/0 opacity-30 cursor-not-allowed') }}"
                                >
                                    <span class="text-xs font-black tracking-widest {{ $slot->status === 'available' ? 'text-slate-400 group-hover:text-white' : 'text-slate-600' }}">{{ $slot->slot_number }}</span>
                                    
                                    <div class="mt-3 relative">
                                        @if($slot->status === 'available')
                                            <div class="absolute inset-0 bg-indigo-500 blur-lg opacity-0 group-hover:opacity-40 transition-opacity"></div>
                                            <svg class="w-7 h-7 text-indigo-500 relative group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        @elseif($slot->status === 'reserved')
                                            <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                        @else
                                            <svg class="w-6 h-6 text-slate-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                        @endif
                                    </div>
                                    
                                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-8 h-1 rounded-full {{ $slot->status === 'available' ? 'bg-indigo-600 opacity-0 group-hover:opacity-100' : '' }} transition-all"></div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Reservation Modal -->
    <div x-show="showReservationModal" class="fixed inset-0 z-[300] overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
            <div @click="showReservationModal = false" class="fixed inset-0 transition-opacity bg-black/90 backdrop-blur-md"></div>
            
            <div class="glass-card inline-block w-full max-w-lg p-10 overflow-hidden text-left align-middle transition-all transform rounded-[3rem] border border-white/10 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.7)]">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-black text-white tracking-tighter">
                        @if($reservationStep === 1) Reservasi Slot {{ $slotToReserve?->slot_number }} @else Pembayaran @endif
                    </h3>
                    <div class="px-4 py-2 bg-indigo-600/20 rounded-full text-[10px] font-black text-indigo-400 uppercase tracking-widest border border-indigo-500/20">Rp 5.000</div>
                </div>
                
                @if($reservationStep === 1)
                <form wire:submit="reserve" class="space-y-6 animate__animated animate__fadeIn">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">Plat Nomor</label>
                            <input wire:model="vehiclePlateNumber" type="text" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="B 1234 ABC">
                            @error('vehiclePlateNumber') <span class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">Model Kendaraan</label>
                            <input wire:model="vehicleModel" type="text" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="Honda Jazz (Putih)">
                            @error('vehicleModel') <span class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all cursor-pointer {{ $selectedPaymentMethod === 'e-wallet' ? 'border-indigo-500 bg-indigo-500/10' : 'border-white/5 bg-white/[0.02] hover:bg-white/[0.05]' }}">
                                <input type="radio" wire:model="selectedPaymentMethod" value="e-wallet" class="absolute opacity-0">
                                <span class="text-[10px] font-bold text-white uppercase tracking-widest">E-Wallet</span>
                            </label>
                            <label class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all cursor-pointer {{ $selectedPaymentMethod === 'qris' ? 'border-indigo-500 bg-indigo-500/10' : 'border-white/5 bg-white/[0.02] hover:bg-white/[0.05]' }}">
                                <input type="radio" wire:model="selectedPaymentMethod" value="qris" class="absolute opacity-0">
                                <span class="text-[10px] font-bold text-white uppercase tracking-widest">QRIS</span>
                            </label>
                            <label class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all cursor-pointer {{ $selectedPaymentMethod === 'credit_card' ? 'border-indigo-500 bg-indigo-500/10' : 'border-white/5 bg-white/[0.02] hover:bg-white/[0.05]' }}">
                                <input type="radio" wire:model="selectedPaymentMethod" value="credit_card" class="absolute opacity-0">
                                <span class="text-[10px] font-bold text-white uppercase tracking-widest">Card</span>
                            </label>
                            <label class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all cursor-pointer {{ $selectedPaymentMethod === 'cash' ? 'border-indigo-500 bg-indigo-500/10' : 'border-white/5 bg-white/[0.02] hover:bg-white/[0.05]' }}">
                                <input type="radio" wire:model="selectedPaymentMethod" value="cash" class="absolute opacity-0">
                                <span class="text-[10px] font-bold text-white uppercase tracking-widest">Cash</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex space-x-4 pt-6">
                        <button type="submit" class="flex-1 btn-primary py-4 rounded-2xl text-white font-bold text-sm tracking-widest uppercase">Lanjut ke Pembayaran</button>
                    </div>
                </form>
                @else
                <div class="space-y-8 animate__animated animate__fadeIn text-center">
                    @if($selectedPaymentMethod === 'qris' || $selectedPaymentMethod === 'e-wallet')
                        <div class="space-y-4">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Scan QRIS Berikut</p>
                            <div class="bg-white p-4 rounded-3xl inline-block shadow-2xl">
                                <img src="{{ asset('images/qris_demo.png') }}" alt="QRIS" class="w-48 h-48">
                            </div>
                            <p class="text-[10px] text-slate-500 italic">Berlaku selama 5 menit</p>
                        </div>
                    @elseif($selectedPaymentMethod === 'credit_card')
                        <div class="space-y-6 text-left">
                            <div class="space-y-4">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Nomor Kartu</label>
                                <input type="text" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium" placeholder="**** **** **** ****">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Expiry</label>
                                    <input type="text" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium" placeholder="MM/YY">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">CVV</label>
                                    <input type="text" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium" placeholder="***">
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="pt-6 space-y-4">
                        <button wire:click="finalizeReservation" class="w-full btn-primary py-4 rounded-2xl text-white font-bold text-sm tracking-widest uppercase flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Selesaikan Pembayaran
                        </button>
                        <button wire:click="$set('reservationStep', 1)" class="w-full text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">Kembali ke Data Kendaraan</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

