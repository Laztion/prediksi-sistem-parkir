<div class="space-y-16" wire:poll.5s>
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
    <div class="grid lg:grid-cols-4 gap-10 items-start">
        <div class="lg:col-span-1 space-y-8 sticky top-24">
            <div class="glass-card p-8 rounded-[2.5rem]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Lokasi Parkir</label>
                <div class="relative group">
                    <select wire:model.live="selectedAreaId" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none appearance-none cursor-pointer group-hover:bg-white/[0.05]">
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" class="bg-slate-900">{{ $area->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Prediction Result -->
            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-600/20 blur-[60px] rounded-full group-hover:bg-indigo-600/30 transition-all duration-700"></div>
                <div class="relative z-10">
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Naive Bayes Prediction</p>
                    </div>
                    
                    <h3 class="text-4xl font-black text-white mb-6 tracking-tighter">
                        {{ $prediction === 'available' ? 'Tersedia' : 'Penuh' }}
                    </h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-slate-400">Confidence</span>
                            <span class="text-indigo-400">{{ $probability }}%</span>
                        </div>
                        <div class="w-full bg-white/[0.05] h-2.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 h-full transition-all duration-1000 cubic-bezier(0.4, 0, 0.2, 1)" style="width: {{ $probability }}%"></div>
                        </div>
                    </div>

                    <p class="text-[10px] text-slate-500 leading-relaxed font-medium italic">
                        Diperbarui otomatis: {{ now()->format('l, H:i:s') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Slot Grid Area -->
        <div class="lg:col-span-3 glass-card p-10 rounded-[3rem]">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
                <div>
                    <h3 class="text-2xl font-extrabold text-white tracking-tight">Pilih Slot Anda</h3>
                    <p class="text-sm text-slate-500 mt-1">Interaktif denah parkir real-time.</p>
                </div>
                <div class="flex bg-white/[0.03] p-1.5 rounded-2xl border border-white/5 space-x-2">
                    <div class="flex items-center px-3 py-1.5 rounded-xl bg-white/[0.05]"><span class="w-2 h-2 bg-indigo-500 rounded-full mr-2"></span> <span class="text-[10px] font-bold text-slate-300">Available</span></div>
                    <div class="flex items-center px-3 py-1.5 rounded-xl"><span class="w-2 h-2 bg-slate-700 rounded-full mr-2"></span> <span class="text-[10px] font-bold text-slate-500">Full</span></div>
                    <div class="flex items-center px-3 py-1.5 rounded-xl"><span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span> <span class="text-[10px] font-bold text-slate-500">Reserved</span></div>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-6">
                @foreach($slots as $slot)
                    <button 
                        wire:click="reserve({{ $slot->id }})"
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
    </div>
</div>

