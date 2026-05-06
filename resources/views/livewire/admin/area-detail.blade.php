<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.areas') }}" class="p-3 bg-white/[0.03] hover:bg-white/10 rounded-2xl transition-colors text-slate-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-white tracking-tight">{{ $area->name }}</h1>
                <p class="text-slate-500 mt-1">{{ $area->location }}</p>
            </div>
        </div>
        
        @if($area->map_image)
        <div x-data="{ open: false }">
            <button @click="open = true" class="px-6 py-3 glass-card border border-white/10 rounded-2xl text-xs font-bold text-white hover:bg-white/5 flex items-center transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                Buka Denah Area
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

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex space-x-4 border-b border-white/10 pb-px">
        <button wire:click="$set('activeTab', 'slots')" class="pb-4 px-2 text-sm font-bold transition-colors border-b-2 {{ $activeTab === 'slots' ? 'border-indigo-500 text-white' : 'border-transparent text-slate-500 hover:text-slate-300' }}">
            Manajemen Slot
        </button>
        <button wire:click="$set('activeTab', 'sectors')" class="pb-4 px-2 text-sm font-bold transition-colors border-b-2 {{ $activeTab === 'sectors' ? 'border-indigo-500 text-white' : 'border-transparent text-slate-500 hover:text-slate-300' }}">
            Manajemen Sektor
        </button>
        <button wire:click="$set('activeTab', 'reservations')" class="pb-4 px-2 text-sm font-bold transition-colors border-b-2 {{ $activeTab === 'reservations' ? 'border-indigo-500 text-white' : 'border-transparent text-slate-500 hover:text-slate-300' }}">
            Daftar Reservasi & User
        </button>
    </div>

    <!-- Tab Content: Slots -->
    @if($activeTab === 'slots')
    <div class="space-y-8 animate__animated animate__fadeIn">
        <!-- Add Slot Form -->
        <div class="glass-card p-8 rounded-[2.5rem]">
            <h3 class="text-lg font-bold text-white mb-6">Tambah Slot Baru</h3>
            <form wire:submit="addSlot" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Nomor Slot</label>
                    <input wire:model="newSlotNumber" type="text" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="Contoh: A01">
                    @error('newSlotNumber') <span class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Tipe Kendaraan</label>
                    <select wire:model="newSlotType" class="w-full bg-slate-900 border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none">
                        <option value="car">Mobil</option>
                        <option value="motorcycle">Motor</option>
                    </select>
                    @error('newSlotType') <span class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Pilih Sektor</label>
                    <select wire:model="selectedSectorId" class="w-full bg-slate-900 border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none">
                        <option value="">Tanpa Sektor</option>
                        @foreach($sectors as $sector)
                        <option value="{{ $sector->id }}">{{ $sector->name }} ({{ $sector->code }})</option>
                        @endforeach
                    </select>
                    @error('selectedSectorId') <span class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn-primary px-8 py-4 rounded-2xl text-white font-bold h-[56px] flex items-center justify-center">
                    Tambah
                </button>
            </form>
        </div>

        <!-- Sectors and Slots -->
        <div class="space-y-10">
            @foreach($sectors as $sector)
            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <h4 class="text-lg font-black text-white tracking-tight">{{ $sector->name }}</h4>
                    <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-lg">{{ $sector->code }}</span>
                </div>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
                    @forelse($sector->parkingSlots as $slot)
                    <div class="glass-card p-4 rounded-2xl text-center relative group overflow-hidden border {{ $slot->status === 'available' ? 'border-white/5' : ($slot->status === 'occupied' ? 'border-red-500/30 bg-red-500/5' : 'border-amber-500/30 bg-amber-500/5') }}">
                        <div class="text-lg font-black text-white mb-2">{{ $slot->slot_number }}</div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">{{ $slot->type }}</div>
                        
                        <button wire:click="toggleSlotStatus({{ $slot->id }})" class="w-full py-2 rounded-xl text-xs font-bold transition-colors {{ $slot->status === 'available' ? 'bg-indigo-500/20 text-indigo-400 hover:bg-indigo-500/40' : 'bg-slate-800 text-slate-400 hover:bg-slate-700' }}">
                            {{ ucfirst($slot->status) }}
                        </button>
                    </div>
                    @empty
                    <div class="col-span-full text-slate-600 text-xs italic">Belum ada slot di sektor ini.</div>
                    @endforelse
                </div>
            </div>
            @endforeach

            @if($unassignedSlots->count() > 0)
            <div class="space-y-4 pt-10 border-t border-white/5">
                <h4 class="text-lg font-black text-slate-400 tracking-tight">Slot Tanpa Sektor</h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
                    @foreach($unassignedSlots as $slot)
                    <div class="glass-card p-4 rounded-2xl text-center relative group overflow-hidden border {{ $slot->status === 'available' ? 'border-white/5' : ($slot->status === 'occupied' ? 'border-red-500/30 bg-red-500/5' : 'border-amber-500/30 bg-amber-500/5') }}">
                        <div class="text-lg font-black text-white mb-2">{{ $slot->slot_number }}</div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">{{ $slot->type }}</div>
                        
                        <button wire:click="toggleSlotStatus({{ $slot->id }})" class="w-full py-2 rounded-xl text-xs font-bold transition-colors {{ $slot->status === 'available' ? 'bg-indigo-500/20 text-indigo-400 hover:bg-indigo-500/40' : 'bg-slate-800 text-slate-400 hover:bg-slate-700' }}">
                            {{ ucfirst($slot->status) }}
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Tab Content: Sectors -->
    @if($activeTab === 'sectors')
    <div class="space-y-8 animate__animated animate__fadeIn">
        <div class="glass-card p-8 rounded-[2.5rem]">
            <h3 class="text-lg font-bold text-white mb-6">Tambah Sektor Baru</h3>
            <form wire:submit="addSector" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Nama Sektor</label>
                    <input wire:model="newSectorName" type="text" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="Contoh: Sayap Timur">
                    @error('newSectorName') <span class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Kode Sektor</label>
                    <input wire:model="newSectorCode" type="text" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="Contoh: A">
                    @error('newSectorCode') <span class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn-primary px-8 py-4 rounded-2xl text-white font-bold h-[56px] flex items-center justify-center">
                    Tambah Sektor
                </button>
            </form>
        </div>

        <div class="glass-card rounded-[2.5rem] overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-white/5">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Nama Sektor</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Kode</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Jumlah Slot</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($sectors as $sector)
                    <tr class="hover:bg-white/[0.01] transition-colors">
                        <td class="px-8 py-6">
                            <div class="font-bold text-white">{{ $sector->name }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-lg">{{ $sector->code }}</span>
                        </td>
                        <td class="px-8 py-6 text-slate-300">
                            {{ $sector->parkingSlots->count() }} Slot
                        </td>
                        <td class="px-8 py-6 text-slate-300">
                            <!-- Action buttons if needed -->
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-slate-500 italic">Belum ada sektor di area ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Tab Content: Reservations -->
    @if($activeTab === 'reservations')
    <div class="space-y-6 animate__animated animate__fadeIn">
        <div class="flex justify-end">
            <button wire:click="exportToExcel" class="btn-primary px-6 py-3 rounded-2xl text-xs font-bold text-white shadow-lg shadow-indigo-500/20 flex items-center transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </button>
        </div>
        <div class="glass-card rounded-[2.5rem] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">User</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Kendaraan</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Slot</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Waktu</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($reservations as $reservation)
                        <tr class="hover:bg-white/[0.01] transition-colors">
                            <td class="px-8 py-6">
                                <div class="font-bold text-white">{{ $reservation->user->name }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">{{ $reservation->user->email }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-bold text-indigo-400 text-xs">{{ $reservation->vehicle_plate_number }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">{{ $reservation->vehicle_model }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-lg bg-white/[0.05] text-white font-black text-xs">{{ $reservation->parkingSlot->slot_number }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm text-slate-300">{{ $reservation->start_time }}</div>
                                <div class="text-[10px] text-slate-500">s/d {{ $reservation->end_time }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs font-bold uppercase {{ $reservation->status === 'active' ? 'text-emerald-400' : ($reservation->status === 'completed' ? 'text-slate-400' : 'text-red-400') }}">
                                    {{ $reservation->status }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-[10px] font-bold text-white uppercase">{{ str_replace('_', ' ', $reservation->payment_method) }}</div>
                                <div class="flex items-center space-x-3 mt-1">
                                    <div class="text-[9px] font-black uppercase {{ $reservation->payment_status === 'paid' ? 'text-emerald-500' : 'text-amber-500' }}">{{ $reservation->payment_status }}</div>
                                    @if($reservation->payment_status === 'pending')
                                        <button wire:click="confirmPayment({{ $reservation->id }})" class="px-2 py-0.5 bg-indigo-500/20 hover:bg-indigo-500/40 text-indigo-400 text-[8px] font-black uppercase rounded transition-colors">
                                            Konfirmasi
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-slate-500 italic">Belum ada reservasi di area ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
