<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informasi Peminjaman</h3>

                            <div class="space-y-3">
                                <div>
                                    <label class="font-medium text-gray-600">Status:</label>
                                    <span class="ml-2">
                                        @if($booking->status == 'approved')
                                            <span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Disetujui</span>
                                        @elseif($booking->status == 'cancelled')
                                            <span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">Dibatalkan</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-sm font-medium px-2.5 py-0.5 rounded">{{ $booking->status }}</span>
                                        @endif
                                    </span>
                                </div>

                                <div>
                                    <label class="font-medium text-gray-600">Ruangan:</label>
                                    <span class="ml-2">{{ $booking->room->name }}</span>
                                </div>

                                <div>
                                    <label class="font-medium text-gray-600">Tanggal:</label>
                                    <span class="ml-2">{{ $booking->booking_date->format('d F Y') }}</span>
                                </div>

                                <div>
                                    <label class="font-medium text-gray-600">Waktu:</label>
                                    <span class="ml-2">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</span>
                                </div>

                                <div>
                                    <label class="font-medium text-gray-600">Durasi:</label>
                                    <span class="ml-2">{{ $booking->getDurationInMinutes() }} menit</span>
                                </div>

                                <div>
                                    <label class="font-medium text-gray-600">Kegiatan:</label>
                                    <p class="mt-1 text-gray-800">{{ $booking->activity }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informasi Peminjam</h3>

                            <div class="space-y-3">
                                <div>
                                    <label class="font-medium text-gray-600">Nama:</label>
                                    <span class="ml-2">{{ $booking->user->name }}</span>
                                </div>

                                <div>
                                    <label class="font-medium text-gray-600">Email:</label>
                                    <span class="ml-2">{{ $booking->user->email }}</span>
                                </div>

                                @if($booking->user->whatsapp)
                                <div>
                                    <label class="font-medium text-gray-600">WhatsApp:</label>
                                    <span class="ml-2">{{ $booking->user->whatsapp }}</span>
                                </div>
                                @endif

                                <div>
                                    <label class="font-medium text-gray-600">Dibuat pada:</label>
                                    <span class="ml-2">{{ $booking->created_at->format('d F Y H:i') }}</span>
                                </div>

                                @if($booking->status == 'cancelled')
                                <div>
                                    <label class="font-medium text-gray-600">Dibatalkan oleh:</label>
                                    <span class="ml-2">{{ $booking->cancelled_by }}</span>
                                </div>

                                @if($booking->cancellation_reason)
                                <div>
                                    <label class="font-medium text-gray-600">Alasan pembatalan:</label>
                                    <p class="mt-1 text-gray-800">{{ $booking->cancellation_reason }}</p>
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('bookings.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                            Kembali
                        </a>

                        @if($booking->canBeCancelled())
                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan peminjaman ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                    Batalkan Peminjaman
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>