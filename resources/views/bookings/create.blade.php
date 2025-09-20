<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Peminjaman Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="room_id" class="block text-sm font-medium text-gray-700">Ruangan</label>
                            <select name="room_id" id="room_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Pilih Ruangan</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }} (Kapasitas: {{ $room->capacity }} orang)
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="booking_date" class="block text-sm font-medium text-gray-700">Tanggal Peminjaman</label>
                            <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('booking_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700">Waktu Mulai</label>
                                <select name="start_time" id="start_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Pilih Waktu</option>
                                    @foreach($timeSlots as $slot)
                                        @if($slot != '20:00')
                                            <option value="{{ $slot }}" {{ old('start_time') == $slot ? 'selected' : '' }}>
                                                {{ $slot }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('start_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700">Durasi</label>
                                <select name="duration" id="duration" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Pilih Durasi</option>
                                    <option value="30" {{ old('duration') == '30' ? 'selected' : '' }}>30 Menit</option>
                                    <option value="60" {{ old('duration') == '60' ? 'selected' : '' }}>1 Jam</option>
                                    <option value="90" {{ old('duration') == '90' ? 'selected' : '' }}>1.5 Jam</option>
                                    <option value="120" {{ old('duration') == '120' ? 'selected' : '' }}>2 Jam</option>
                                </select>
                                @error('duration')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="activity" class="block text-sm font-medium text-gray-700">Kegiatan</label>
                            <textarea name="activity" id="activity" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Jelaskan kegiatan yang akan dilakukan..."
                                      required>{{ old('activity') }}</textarea>
                            @error('activity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @error('time')
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                {{ $message }}
                            </div>
                        @enderror

                        <div id="availability-info" class="mb-4 hidden">
                            <h4 class="font-semibold mb-2">Slot Terpakai:</h4>
                            <div id="booked-slots" class="space-y-2"></div>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('bookings.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Buat Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roomSelect = document.getElementById('room_id');
            const dateInput = document.getElementById('booking_date');

            function checkAvailability() {
                const roomId = roomSelect.value;
                const date = dateInput.value;

                if (roomId && date) {
                    fetch('{{ route("bookings.check-availability") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            room_id: roomId,
                            date: date
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const availabilityInfo = document.getElementById('availability-info');
                        const bookedSlots = document.getElementById('booked-slots');

                        if (data.length > 0) {
                            availabilityInfo.classList.remove('hidden');
                            bookedSlots.innerHTML = data.map(booking => {
                                const userName = '{{ auth()->user()->name }}';
                                const roomName = roomSelect.options[roomSelect.selectedIndex].text.split(' (')[0];
                                const startTime = booking.start_time.substr(0, 5);
                                const endTime = booking.end_time.substr(0, 5);

                                // Format WhatsApp message
                                let waMessage = `Shalom saya ${userName}, permisi saya mau berbicara mengenai peminjaman ruang ${roomName} di jam ${startTime} - ${endTime}.`;
                                waMessage = encodeURIComponent(waMessage);

                                // Format phone number (remove leading 0 and add 62 for Indonesia)
                                let waNumber = booking.user.whatsapp;
                                if (waNumber && waNumber.startsWith('0')) {
                                    waNumber = '62' + waNumber.substr(1);
                                }

                                return `<div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="font-semibold text-red-800">${startTime} - ${endTime}</span>
                                            <div class="text-sm text-gray-600 mt-1">
                                                <div>Peminjam: ${booking.user.name}</div>
                                                <div>Kegiatan: ${booking.activity}</div>
                                            </div>
                                        </div>
                                        ${waNumber ? `
                                        <a href="https://wa.me/${waNumber}?text=${waMessage}"
                                           target="_blank"
                                           class="inline-flex items-center bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            Hubungi via WA
                                        </a>` : ''}
                                    </div>
                                </div>`;
                            }).join('');
                        } else {
                            availabilityInfo.classList.add('hidden');
                        }
                    });
                }
            }

            roomSelect.addEventListener('change', checkAvailability);
            dateInput.addEventListener('change', checkAvailability);
        });
    </script>
</x-app-layout>