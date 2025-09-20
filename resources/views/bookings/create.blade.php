<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Buat Peminjaman Baru
        </h2>
        <p class="text-blue-100 text-sm mt-1">Isi form di bawah untuk membuat peminjaman ruangan</p>
    </x-slot>

    <div class="py-12 min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-center">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">1</div>
                            <span class="ml-2 text-sm font-medium text-gray-900">Pilih Ruangan</span>
                        </div>
                        <div class="w-16 h-0.5 bg-gray-300"></div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">2</div>
                            <span class="ml-2 text-sm font-medium text-gray-500">Atur Waktu</span>
                        </div>
                        <div class="w-16 h-0.5 bg-gray-300"></div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold">3</div>
                            <span class="ml-2 text-sm font-medium text-gray-500">Detail Kegiatan</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-8">
                    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Room Selection Card -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                            <label for="room_id" class="block text-sm font-semibold text-gray-800 mb-3">
                                <svg class="w-5 h-5 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Pilih Ruangan
                            </label>
                            <select name="room_id" id="room_id" class="w-full px-4 py-3 rounded-lg border-2 border-blue-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }} (Kapasitas: {{ $room->capacity }} orang)
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Date Selection -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                            <label for="booking_date" class="block text-sm font-semibold text-gray-800 mb-3">
                                <svg class="w-5 h-5 inline-block mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tanggal Peminjaman
                            </label>
                            <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 rounded-lg border-2 border-purple-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200"
                                   required>
                            @error('booking_date')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Time Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-xl p-6 border border-green-100">
                                <label for="start_time" class="block text-sm font-semibold text-gray-800 mb-3">
                                    <svg class="w-5 h-5 inline-block mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Waktu Mulai
                                </label>
                                <select name="start_time" id="start_time" class="w-full px-4 py-3 rounded-lg border-2 border-green-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200" required>
                                    <option value="">-- Pilih Waktu --</option>
                                    @foreach($timeSlots as $slot)
                                        @if($slot != '20:00')
                                            <option value="{{ $slot }}" {{ old('start_time') == $slot ? 'selected' : '' }}>
                                                {{ $slot }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('start_time')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-100">
                                <label for="duration" class="block text-sm font-semibold text-gray-800 mb-3">
                                    <svg class="w-5 h-5 inline-block mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Durasi
                                </label>
                                <select name="duration" id="duration" class="w-full px-4 py-3 rounded-lg border-2 border-yellow-200 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200" required>
                                    <option value="">-- Pilih Durasi --</option>
                                    <option value="30" {{ old('duration') == '30' ? 'selected' : '' }}>30 Menit</option>
                                    <option value="60" {{ old('duration') == '60' ? 'selected' : '' }}>1 Jam</option>
                                    <option value="90" {{ old('duration') == '90' ? 'selected' : '' }}>1.5 Jam</option>
                                    <option value="120" {{ old('duration') == '120' ? 'selected' : '' }}>2 Jam</option>
                                </select>
                                @error('duration')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Activity Details -->
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-6 border border-indigo-100">
                            <label for="activity" class="block text-sm font-semibold text-gray-800 mb-3">
                                <svg class="w-5 h-5 inline-block mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Detail Kegiatan
                            </label>
                            <textarea name="activity" id="activity" rows="4"
                                      class="w-full px-4 py-3 rounded-lg border-2 border-indigo-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                                      placeholder="Jelaskan kegiatan yang akan dilakukan di ruangan ini..."
                                      required>{{ old('activity') }}</textarea>
                            @error('activity')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        @error('time')
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-red-800">{{ $message }}</p>
                                </div>
                            </div>
                        @enderror

                        <!-- Availability Info -->
                        <div id="availability-info" class="hidden">
                            <div class="bg-orange-50 rounded-xl p-6 border border-orange-200">
                                <h4 class="font-semibold text-orange-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    Slot yang Sudah Terpakai
                                </h4>
                                <div id="booked-slots" class="space-y-2"></div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                            <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
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