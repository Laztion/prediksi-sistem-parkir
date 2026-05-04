<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10" x-data="{ showModal: @entangle('showModal') }">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">Manajemen Area Parkir</h1>
            <p class="text-slate-500 mt-1">Kelola lokasi dan kapasitas parkir SmartPark.</p>
        </div>
        <button @click="showModal = true" class="btn-primary px-6 py-3 rounded-2xl text-sm font-bold text-white shadow-lg shadow-indigo-500/20 flex items-center transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Lokasi Baru
        </button>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($areas as $area)
        <div class="glass-card rounded-[2.5rem] overflow-hidden group hover:border-indigo-500/30 transition-all duration-500">
            <div class="p-8 space-y-6">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.areas.detail', $area->id) }}" class="p-2 hover:bg-white/5 rounded-xl transition text-slate-400 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <button wire:click="deleteArea({{ $area->id }})" wire:confirm="Yakin ingin menghapus area ini?" class="p-2 hover:bg-red-500/10 rounded-xl transition text-slate-400 hover:text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-white tracking-tight">{{ $area->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $area->location }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/5">
                    <div>
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Kapasitas</p>
                        <p class="text-lg font-bold text-white">{{ $area->total_slots }} Slot</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Terisi</p>
                        <p class="text-lg font-bold text-indigo-400">{{ $area->parkingSlots()->where('status', 'occupied')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('admin.areas.detail', $area->id) }}" class="block py-4 bg-white/[0.02] hover:bg-indigo-600 transition-colors text-center text-xs font-bold text-slate-400 hover:text-white uppercase tracking-widest">
                Kelola Slot & User
            </a>
        </div>
        @endforeach
    </div>

    <!-- Modal Form -->
    <div x-show="showModal" class="fixed inset-0 z-[200] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
            <div @click="showModal = false" class="fixed inset-0 transition-opacity bg-black/80 backdrop-blur-sm"></div>
            
            <div class="glass-card inline-block w-full max-w-xl p-10 overflow-hidden text-left align-middle transition-all transform rounded-[3rem] border border-white/10">
                <h3 class="text-2xl font-black text-white tracking-tighter mb-8">Tambah Lokasi Baru</h3>
                
                <form wire:submit="saveArea" class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Nama Area</label>
                        <input wire:model="name" type="text" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="Contoh: Grand Indonesia">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Lokasi/Alamat</label>
                        <input wire:model="location" type="text" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="Kota, Wilayah">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Total Slot</label>
                            <input wire:model="total_slots" type="number" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Deskripsi</label>
                        <textarea wire:model="description" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none h-32"></textarea>
                    </div>

                    <div class="flex space-x-4 pt-4">
                        <button type="submit" class="flex-1 btn-primary py-4 rounded-2xl text-white font-bold">Simpan Lokasi</button>
                        <button @click="showModal = false" type="button" class="px-8 py-4 glass-card border border-white/10 rounded-2xl text-white font-bold hover:bg-white/5">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
