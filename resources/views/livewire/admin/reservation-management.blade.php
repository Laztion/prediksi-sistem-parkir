<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 animate__animated animate__fadeIn">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">Manajemen Reservasi</h1>
            <p class="text-slate-500 mt-1">Verifikasi kedatangan dan kelola status parkir pengguna.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-2xl text-sm font-bold text-white glass-card border border-white/10 hover:bg-white/5 transition-all flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard
        </a>
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
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Pengguna</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Area & Slot</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Waktu</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($reservations as $reservation)
                        <tr class="group hover:bg-white/[0.01] transition-colors">
                            <td class="px-8 py-6">
                                <div class="font-bold text-white">{{ $reservation->user->name }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">{{ $reservation->user->email }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-bold text-white">{{ $reservation->parkingSlot->parkingArea->name }}</div>
                                <div class="flex items-center mt-1">
                                    <span class="px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-400 font-black text-[10px]">{{ $reservation->parkingSlot->slot_number }}</span>
                                    <span class="ml-2 text-[10px] text-slate-600 italic uppercase">{{ $reservation->parkingSlot->type }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-xs text-white font-medium">{{ $reservation->start_time->format('d M, H:i') }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">Sampai {{ $reservation->end_time->format('H:i') }}</div>
                            </td>
                            <td class="px-8 py-6">
                                @if($reservation->status == 'pending')
                                    <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 font-black text-[10px] uppercase tracking-tighter border border-amber-500/20">Menunggu</span>
                                @elseif($reservation->status == 'active')
                                    <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 font-black text-[10px] uppercase tracking-tighter border border-emerald-500/20 animate-pulse">Parkir Aktif</span>
                                @elseif($reservation->status == 'completed')
                                    <span class="px-3 py-1 rounded-full bg-slate-800/50 text-slate-500 font-black text-[10px] uppercase tracking-tighter border border-white/5">Selesai</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-500/10 text-red-500/50 font-black text-[10px] uppercase tracking-tighter border border-red-500/10">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right space-x-2">
                                @if($reservation->status == 'pending')
                                    <button wire:click="verifyCheckIn({{ $reservation->id }})" class="px-4 py-2 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-500/20 transition-all">Verifikasi</button>
                                    <button wire:click="cancelReservation({{ $reservation->id }})" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-red-500/10 text-slate-500 hover:text-red-500 text-[10px] font-black uppercase tracking-widest border border-white/5 transition-all">Batal</button>
                                @elseif($reservation->status == 'active')
                                    <button wire:click="completeReservation({{ $reservation->id }})" class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20 transition-all">Selesai</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-500 italic">Belum ada data reservasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 border-t border-white/5 bg-white/[0.01]">
            {{ $reservations->links() }}
        </div>
    </div>
</div>
