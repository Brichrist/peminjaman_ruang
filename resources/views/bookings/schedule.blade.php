<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Jadwal Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('bookings.schedule') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="room_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Ruangan</label>
                                <select name="room_id" id="room_id" class="block w-full rounded-md border-gray-300 shadow-sm" onchange="this.form.submit()">
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }} (Kapasitas: {{ $room->capacity }} orang)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" name="date" id="date" value="{{ $selectedDate }}" min="{{ date('Y-m-d') }}" class="block w-full rounded-md border-gray-300 shadow-sm" onchange="this.form.submit()">
                            </div>
                            <div class="flex items-end">
                                <a href="{{ route('bookings.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Buat Peminjaman Baru
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Information Note -->
                    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm text-blue-700">
                                <strong>Catatan:</strong> Silahkan booking ruangan dari waktu mulai hingga waktu selesai acara
                            </p>
                        </div>
                    </div>

                    @if ($selectedRoom)
                        <h3 class="text-lg font-semibold mb-4">
                            Jadwal {{ $selectedRoom->name }} - {{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}
                        </h3>

                        <!-- Time Slots Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach ($timeSlots as $index => $slot)
                                @if ($index < count($timeSlots) - 1)
                                    @php
                                        $nextSlot = $timeSlots[$index + 1];
                                        $isBooked = false;
                                        $booking = null;

                                        foreach ($bookings as $b) {
                                            if ($b->start_time <= $slot . ':00' && $b->end_time > $slot . ':00') {
                                                $isBooked = true;
                                                $booking = $b;
                                                break;
                                            }
                                        }

                                        $roomName = $selectedRoom->name;
                                    @endphp

                                    <div class="border {{ $isBooked ? 'bg-red-50 border-red-300' : 'bg-green-50 border-green-300' }} rounded-lg p-3">
                                        <div class="font-medium mb-1">{{ $slot }} - {{ $nextSlot }}</div>

                                        @if ($isBooked)
                                            <div class="text-sm text-gray-700">
                                                <p class="font-semibold">{{ $booking->getContactName() }}</p>
                                                <p class="text-xs mt-1">{{ $booking->activity }}</p>
                                            </div>

                                            @if ($booking->getFormattedWhatsApp())
                                                @php
                                                    $waMessage = "Shalom saya, permisi saya mau berbicara mengenai peminjaman ruang {$roomName} di jam {$slot} - {$nextSlot}.";
                                                    $waMessage = urlencode($waMessage);
                                                    $waNumber = $booking->getFormattedWhatsApp();
                                                @endphp

                                                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="inline-flex items-center mt-2 bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                                    </svg>
                                                    Hubungi
                                                </a>
                                            @endif
                                        @else
                                            <div class="text-sm text-green-600 font-medium">Tersedia</div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Legend -->
                        <div class="mt-6 flex gap-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-50 border border-green-300 rounded mr-2"></div>
                                <span>Tersedia</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-50 border border-red-300 rounded mr-2"></div>
                                <span>Terpakai</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            Silakan pilih ruangan dan tanggal untuk melihat jadwal.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
