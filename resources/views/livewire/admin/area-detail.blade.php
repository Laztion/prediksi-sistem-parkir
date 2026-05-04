<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
    <!-- Header -->
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('admin.areas') }}" class="p-3 bg-white/[0.03] hover:bg-white/10 rounded-2xl transition-colors text-slate-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">{{ $area->name }}</h1>
            <p class="text-slate-500 mt-1">{{ $area->location }}</p>
        </div>
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
            <form wire:submit="addSlot" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Nomor Slot</label>
                    <input wire:model="newSlotNumber" type="text" class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="Contoh: A01">
                    @error('newSlotNumber') <span class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Tipe Kendaraan</label>
                    <select wire:model="newSlotType" class="w-full bg-slate-900 border border-white/5 rounded-2xl px-5 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none">
                        <option value="car">Mobil</option>
                        <option value="motorcycle">Motor</option>
                    </select>
                    @error('newSlotType') <span class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn-primary px-8 py-4 rounded-2xl text-white font-bold h-[56px] flex items-center justify-center">
                    Tambah
                </button>
            </form>
        </div>

        <!-- Slots Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
            @foreach($area->parkingSlots as $slot)
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

    <!-- Tab Content: Reservations -->
    @if($activeTab === 'reservations')
    <div class="animate__animated animate__fadeIn">
        <div class="glass-card rounded-[2.5rem] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">User</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Slot</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Waktu</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Status</th>
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
