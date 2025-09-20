<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal & Peminjaman Ruangan
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Date Selector -->
            <div class="bg-white rounded-xl shadow-sm mb-6 p-4">
                <div class="flex flex-col sm:flex-row gap-4 items-center">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
                        <input type="date" id="booking-date"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ date('Y-m-d') }}"
                               min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Ruangan</label>
                        <select id="room-filter" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Ruangan</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button onclick="refreshSchedule()"
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Room Cards Grid -->
            <div id="room-grid" class="grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
                @foreach($rooms as $room)
                <div class="room-card bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-200" data-room-id="{{ $room->id }}">
                    <!-- Room Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4">
                        <h3 class="text-lg font-semibold">{{ $room->name }}</h3>
                        <div class="flex items-center gap-4 mt-2 text-sm">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Kapasitas: {{ $room->capacity }}
                            </span>
                            @if($room->status == 'available')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Tersedia</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">Tidak Tersedia</span>
                            @endif
                        </div>
                    </div>

                    <!-- Time Slots Grid -->
                    <div class="p-4">
                        <p class="text-sm text-gray-600 mb-3">{{ $room->description }}</p>

                        <div class="grid grid-cols-4 gap-2 time-slots-container" data-room-id="{{ $room->id }}">
                            @php
                                $timeSlots = [];
                                $startHour = 8;
                                $endHour = 20;

                                for ($hour = $startHour; $hour < $endHour; $hour++) {
                                    $timeSlots[] = sprintf('%02d:00', $hour);
                                    $timeSlots[] = sprintf('%02d:30', $hour);
                                }
                            @endphp

                            @foreach($timeSlots as $slot)
                                <button
                                    class="time-slot px-2 py-2 text-xs font-medium rounded-lg transition-all duration-200
                                           bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700 border border-gray-200 hover:border-blue-300"
                                    data-time="{{ $slot }}"
                                    data-room-id="{{ $room->id }}"
                                    data-room-name="{{ $room->name }}"
                                    onclick="toggleTimeSlot(this)">
                                    {{ $slot }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Quick Action Buttons -->
                        <div class="mt-4 flex gap-2">
                            <button onclick="selectAllAvailable({{ $room->id }})"
                                    class="flex-1 text-sm py-2 px-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-200">
                                Pilih Semua Tersedia
                            </button>
                            <button onclick="clearSelection({{ $room->id }})"
                                    class="flex-1 text-sm py-2 px-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-200">
                                Hapus Pilihan
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Floating Action Button -->
            <div id="floating-action" class="hidden fixed bottom-6 right-6 bg-white rounded-xl shadow-lg p-4 z-40">
                <div class="text-sm font-medium text-gray-700 mb-2">
                    <span id="selected-count">0</span> slot terpilih
                </div>
                <button onclick="openBookingModal()"
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                    Buat Peminjaman
                </button>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="booking-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900">Formulir Peminjaman</h3>
                    <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <form id="booking-form" method="POST" action="{{ route('bookings.store') }}" class="p-6">
                @csrf
                <input type="hidden" name="room_id" id="modal-room-id">
                <input type="hidden" name="booking_date" id="modal-booking-date">
                <input type="hidden" name="start_time" id="modal-start-time">
                <input type="hidden" name="end_time" id="modal-end-time">

                <!-- Summary -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h4 class="font-medium text-blue-900 mb-2">Ringkasan Peminjaman</h4>
                    <div class="space-y-1 text-sm text-blue-700">
                        <p>Ruangan: <span id="modal-room-name" class="font-medium"></span></p>
                        <p>Tanggal: <span id="modal-date-display" class="font-medium"></span></p>
                        <p>Waktu: <span id="modal-time-display" class="font-medium"></span></p>
                        <p>Durasi: <span id="modal-duration" class="font-medium"></span></p>
                    </div>
                </div>

                <!-- Activity Field -->
                <div class="mb-6">
                    <label for="activity" class="block text-sm font-medium text-gray-700 mb-2">
                        Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="activity"
                        id="activity"
                        rows="3"
                        required
                        placeholder="Deskripsikan kegiatan yang akan dilakukan..."
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <!-- Participant Count (Optional) -->
                <div class="mb-6">
                    <label for="participant_count" class="block text-sm font-medium text-gray-700 mb-2">
                        Estimasi Peserta
                    </label>
                    <input
                        type="number"
                        name="participant_count"
                        id="participant_count"
                        min="1"
                        placeholder="Jumlah peserta (opsional)"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Contact Confirmation -->
                <div class="mb-6">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="confirm_contact" class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-600">
                            Saya bersedia dihubungi melalui WhatsApp ({{ auth()->user()->whatsapp }})
                            jika ada yang perlu konfirmasi terkait peminjaman ini.
                        </span>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="button" onclick="closeBookingModal()"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                        Konfirmasi Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- WhatsApp Contact Modal -->
    <div id="wa-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-sm w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Hubungi Peminjam</h3>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-600 mb-2">Peminjam:</p>
                    <p class="font-medium text-gray-900" id="wa-borrower-name"></p>
                    <p class="text-sm text-gray-600 mt-3 mb-2">Kegiatan:</p>
                    <p class="text-gray-900" id="wa-activity"></p>
                </div>
                <div class="flex gap-3">
                    <button onclick="closeWaModal()"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200">
                        Tutup
                    </button>
                    <a id="wa-link" href="#" target="_blank"
                       class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 text-center">
                        Buka WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let selectedSlots = new Map(); // Map of roomId -> Set of selected times
        let existingBookings = new Map(); // Map of roomId -> bookings array

        // Make functions globally accessible
        window.toggleTimeSlot = toggleTimeSlot;
        window.selectAllAvailable = selectAllAvailable;
        window.clearSelection = clearSelection;
        window.openBookingModal = openBookingModal;
        window.closeBookingModal = closeBookingModal;
        window.refreshSchedule = refreshSchedule;
        window.closeWaModal = closeWaModal;

        function initializePage() {
            document.getElementById('booking-date').addEventListener('change', refreshSchedule);
            document.getElementById('room-filter').addEventListener('change', filterRooms);
            refreshSchedule();
        }

        function filterRooms() {
            const filterId = document.getElementById('room-filter').value;
            const roomCards = document.querySelectorAll('.room-card');

            roomCards.forEach(card => {
                if (filterId === '' || card.dataset.roomId === filterId) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        async function refreshSchedule() {
            const date = document.getElementById('booking-date').value;

            // Clear all selections
            selectedSlots.clear();
            updateFloatingAction();

            // Fetch bookings for all rooms
            const rooms = @json($rooms->pluck('id'));

            for (const roomId of rooms) {
                await fetchRoomBookings(roomId, date);
            }
        }

        async function fetchRoomBookings(roomId, date) {
            try {
                const response = await fetch('{{ route("bookings.check-availability") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        room_id: roomId,
                        date: date
                    })
                });

                const data = await response.json();
                existingBookings.set(roomId, data.bookings || []);
                updateRoomSlots(roomId);
            } catch (error) {
                console.error('Error fetching bookings:', error);
            }
        }

        function updateRoomSlots(roomId) {
            const container = document.querySelector(`.time-slots-container[data-room-id="${roomId}"]`);
            if (!container) return;

            const bookings = existingBookings.get(roomId) || [];
            const slots = container.querySelectorAll('.time-slot');

            slots.forEach(slot => {
                const slotTime = slot.dataset.time + ':00';
                let isBooked = false;
                let bookingInfo = null;

                for (const booking of bookings) {
                    if (isTimeInRange(slotTime, booking.start_time, booking.end_time)) {
                        isBooked = true;
                        bookingInfo = booking;
                        break;
                    }
                }

                // Reset classes
                slot.className = 'time-slot px-2 py-2 text-xs font-medium rounded-lg transition-all duration-200';

                if (isBooked) {
                    slot.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200', 'cursor-pointer');
                    slot.disabled = true;
                    slot.onclick = () => showWaContact(bookingInfo);
                } else if (selectedSlots.get(roomId)?.has(slot.dataset.time)) {
                    slot.classList.add('bg-blue-600', 'text-white', 'ring-2', 'ring-blue-400');
                    slot.onclick = () => toggleTimeSlot(slot);
                } else {
                    slot.classList.add('bg-gray-50', 'hover:bg-blue-50', 'text-gray-700', 'hover:text-blue-700',
                                     'border', 'border-gray-200', 'hover:border-blue-300');
                    slot.onclick = () => toggleTimeSlot(slot);
                }
            });
        }

        function isTimeInRange(time, startTime, endTime) {
            return time >= startTime && time < endTime;
        }

        function toggleTimeSlot(button) {
            const roomId = parseInt(button.dataset.roomId);
            const time = button.dataset.time;

            if (!selectedSlots.has(roomId)) {
                selectedSlots.set(roomId, new Set());
            }

            const roomSlots = selectedSlots.get(roomId);

            if (roomSlots.has(time)) {
                roomSlots.delete(time);
            } else {
                // Clear other room selections when selecting a new room
                if (selectedSlots.size > 0) {
                    for (const [otherRoomId, slots] of selectedSlots) {
                        if (otherRoomId !== roomId && slots.size > 0) {
                            if (!confirm('Anda sudah memilih slot di ruangan lain. Hapus pilihan sebelumnya?')) {
                                return;
                            }
                            selectedSlots.clear();
                            selectedSlots.set(roomId, new Set());
                            break;
                        }
                    }
                }
                roomSlots.add(time);
            }

            updateRoomSlots(roomId);
            updateFloatingAction();
        }

        function selectAllAvailable(roomId) {
            const container = document.querySelector(`.time-slots-container[data-room-id="${roomId}"]`);
            const bookings = existingBookings.get(roomId) || [];
            const availableSlots = [];

            container.querySelectorAll('.time-slot').forEach(slot => {
                const slotTime = slot.dataset.time + ':00';
                let isBooked = false;

                for (const booking of bookings) {
                    if (isTimeInRange(slotTime, booking.start_time, booking.end_time)) {
                        isBooked = true;
                        break;
                    }
                }

                if (!isBooked) {
                    availableSlots.push(slot.dataset.time);
                }
            });

            if (availableSlots.length > 0) {
                selectedSlots.clear();
                selectedSlots.set(roomId, new Set(availableSlots));
                updateRoomSlots(roomId);
                updateFloatingAction();
            }
        }

        function clearSelection(roomId) {
            if (selectedSlots.has(roomId)) {
                selectedSlots.get(roomId).clear();
                updateRoomSlots(roomId);
                updateFloatingAction();
            }
        }

        function updateFloatingAction() {
            const floatingAction = document.getElementById('floating-action');
            let totalSelected = 0;

            for (const slots of selectedSlots.values()) {
                totalSelected += slots.size;
            }

            if (totalSelected > 0) {
                document.getElementById('selected-count').textContent = totalSelected;
                floatingAction.classList.remove('hidden');
            } else {
                floatingAction.classList.add('hidden');
            }
        }

        function openBookingModal() {
            // Get selected room and slots
            let selectedRoomId = null;
            let selectedTimes = [];

            for (const [roomId, slots] of selectedSlots) {
                if (slots.size > 0) {
                    selectedRoomId = roomId;
                    selectedTimes = Array.from(slots).sort();
                    break;
                }
            }

            if (!selectedRoomId || selectedTimes.length === 0) {
                alert('Pilih minimal satu slot waktu');
                return;
            }

            // Check if slots are consecutive
            const consecutive = checkConsecutive(selectedTimes);
            if (!consecutive) {
                alert('Slot waktu yang dipilih harus berurutan');
                return;
            }

            // Get room info
            const roomCard = document.querySelector(`.room-card[data-room-id="${selectedRoomId}"]`);
            const roomName = roomCard.querySelector('h3').textContent;
            const date = document.getElementById('booking-date').value;

            // Calculate time range
            const startTime = selectedTimes[0];
            const endTimeHour = parseInt(selectedTimes[selectedTimes.length - 1].split(':')[0]);
            const endTimeMin = parseInt(selectedTimes[selectedTimes.length - 1].split(':')[1]);
            const endTime = `${String(endTimeMin === 30 ? endTimeHour + 1 : endTimeHour).padStart(2, '0')}:${endTimeMin === 30 ? '00' : '30'}`;

            // Calculate duration
            const duration = selectedTimes.length * 0.5;
            const durationText = duration >= 1 ? `${duration} jam` : '30 menit';

            // Fill modal data
            document.getElementById('modal-room-id').value = selectedRoomId;
            document.getElementById('modal-room-name').textContent = roomName;
            document.getElementById('modal-booking-date').value = date;
            document.getElementById('modal-date-display').textContent = formatDate(date);
            document.getElementById('modal-start-time').value = startTime;
            document.getElementById('modal-end-time').value = endTime;
            document.getElementById('modal-time-display').textContent = `${startTime} - ${endTime}`;
            document.getElementById('modal-duration').textContent = durationText;

            // Show modal
            document.getElementById('booking-modal').classList.remove('hidden');
        }

        function closeBookingModal() {
            document.getElementById('booking-modal').classList.add('hidden');
            document.getElementById('booking-form').reset();
        }

        function checkConsecutive(times) {
            if (times.length === 1) return true;

            for (let i = 1; i < times.length; i++) {
                const prev = timeToMinutes(times[i - 1]);
                const curr = timeToMinutes(times[i]);

                if (curr - prev !== 30) {
                    return false;
                }
            }
            return true;
        }

        function timeToMinutes(time) {
            const [hour, minute] = time.split(':').map(Number);
            return hour * 60 + minute;
        }

        function formatDate(dateString) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        function showWaContact(booking) {
            if (!booking.user || !booking.user.whatsapp) {
                alert('Informasi kontak tidak tersedia');
                return;
            }

            const roomName = document.querySelector(`.room-card h3`).textContent;
            const date = document.getElementById('booking-date').value;
            const userName = '{{ auth()->user()->name }}';

            document.getElementById('wa-borrower-name').textContent = booking.user.name;
            document.getElementById('wa-activity').textContent = booking.activity || 'Tidak ada keterangan';

            const message = `Shalom saya ${userName}, permisi saya mau berbicara mengenai peminjaman ruang ${roomName} di jam ${booking.start_time.substr(0, 5)} - ${booking.end_time.substr(0, 5)}.`;
            const waNumber = booking.user.whatsapp.startsWith('0') ?
                            '62' + booking.user.whatsapp.substr(1) :
                            booking.user.whatsapp;
            const waLink = `https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`;

            document.getElementById('wa-link').href = waLink;
            document.getElementById('wa-modal').classList.remove('hidden');
        }

        function closeWaModal() {
            document.getElementById('wa-modal').classList.add('hidden');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initializePage);
    </script>
    @endpush
</x-app-layout>