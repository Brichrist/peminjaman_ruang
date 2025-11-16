<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Daftar Peminjaman
        </h2>
        <p class="text-blue-100 text-sm mt-1">Kelola dan pantau riwayat peminjaman ruangan Anda</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Riwayat Peminjaman</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('bookings.room-schedule') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                Lihat Jadwal Ruangan
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">Ruangan</th>
                                    <th class="px-4 py-2 text-left">Tanggal</th>
                                    <th class="px-4 py-2 text-left">Waktu</th>
                                    <th class="px-4 py-2 text-left">Kegiatan</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    @if (auth()->user()?->isAdmin() ?? null)
                                        <th class="px-4 py-2 text-left">Peminjam</th>
                                    @endif
                                    <th class="px-4 py-2 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $booking->room->name }}</td>
                                        <td class="px-4 py-2">{{ $booking->booking_date->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</td>
                                        <td class="px-4 py-2">{{ Str::limit($booking->activity, 30) }}</td>
                                        <td class="px-4 py-2">
                                            @if ($booking->status == 'approved')
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Disetujui</span>
                                            @elseif($booking->status == 'cancelled')
                                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Dibatalkan</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $booking->status }}</span>
                                            @endif
                                        </td>
                                        @if (auth()->user()?->isAdmin() ?? null)
                                            <td class="px-4 py-2">
                                                {{ $booking->user->name }}<br>
                                                <small>{{ $booking->user->email }}</small>
                                            </td>
                                        @endif
                                        <td class="px-4 py-2">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('bookings.show', $booking) }}" class="text-blue-600 hover:underline">Lihat</a>
                                                @if ($booking->canBeCancelled())
                                                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan peminjaman ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:underline">Batalkan</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()?->isAdmin() ?? null ? 7 : 6 }}" class="px-4 py-2 text-center text-gray-500">
                                            Belum ada peminjaman.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
