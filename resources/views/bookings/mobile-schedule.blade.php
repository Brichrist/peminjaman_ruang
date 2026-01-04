<x-app-layout>
    @include('components.toast-notification')

    <!-- Mobile-First Design -->
    <div class="min-h-screen bg-gray-50 overflow-x-hidden">
        <!-- Header -->
        <div class="sticky top-0 z-30 bg-white shadow-sm overflow-hidden">
            <div class="px-4 py-3">
                <h1 class="text-lg font-bold text-gray-900 truncate">Booking Ruangan</h1>
                <p class="text-xs text-gray-500 truncate">Pilih ruangan dan waktu yang tersedia</p>
            </div>
        </div>

        <!-- Date & Filter Section -->
        <div class="bg-white border-b sticky top-[4.5rem] z-20 overflow-hidden">
            <div class="px-4 py-3 space-y-3 max-w-full">
                <!-- Date Picker -->
                <div class="flex items-center gap-2 min-w-0">
                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap flex-shrink-0">Tanggal:</label>
                    <input type="date" id="booking-date"
                           class="flex-1 min-w-0 text-sm rounded-lg border-gray-300 shadow-sm"
                           value="{{ date('Y-m-d') }}"
                           min="{{ date('Y-m-d') }}">
                </div>

                <!-- Room Filter -->
                <div class="flex items-center gap-2 min-w-0">
                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap flex-shrink-0">Ruangan:</label>
                    <select id="room-filter" class="flex-1 min-w-0 text-sm rounded-lg border-gray-300 shadow-sm truncate">
                        <option value="">Semua Ruangan</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ Str::limit($room->name, 30) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-2">
                    <button id="filter-time-btn"
                            class="py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Filter Jam
                    </button>
                    <button id="refresh-btn"
                            class="py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Information Note -->
        <div class="mx-4 mt-4 bg-blue-50 border-l-4 border-blue-500 p-3 rounded-r-lg overflow-hidden">
            <div class="flex items-start min-w-0">
                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-xs text-blue-700 min-w-0">
                    <strong>Catatan:</strong> Silahkan booking ruangan dari waktu mulai hingga waktu selesai acara
                </p>
            </div>
        </div>

        <!-- Room Cards - Mobile Optimized -->
        <div class="px-4 py-4 space-y-4 overflow-hidden" id="room-grid">
            @foreach($rooms as $room)
            <div class="room-card bg-white rounded-xl shadow-sm overflow-hidden" data-room-id="{{ $room->id }}">
                <!-- Room Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-3 overflow-hidden">
                    <h3 class="font-semibold text-sm truncate">{{ $room->name }}</h3>
                    <div class="flex items-center gap-3 mt-1 text-xs">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ $room->capacity }} orang
                        </span>
                        @if($room->status == 'available')
                            <span class="bg-green-400 bg-opacity-30 text-white px-2 py-0.5 rounded-full text-xs">Tersedia</span>
                        @else
                            <span class="bg-red-400 bg-opacity-30 text-white px-2 py-0.5 rounded-full text-xs">Tidak Tersedia</span>
                        @endif
                    </div>
                </div>

                <!-- Room Description -->
                <div class="px-3 pt-3 pb-2 overflow-hidden">
                    <p class="text-xs text-gray-600 break-words">{{ Str::limit($room->description, 60) }}</p>
                </div>

                <!-- Time Slots Grid - Mobile Optimized -->
                <div class="px-3 pb-3 overflow-hidden">
                    <div class="grid grid-cols-4 gap-1.5 time-slots-container w-full" data-room-id="{{ $room->id }}">
                        @php
                            $timeSlots = [];
                            $startHour = 5;
                            $endHour = 23;

                            for ($hour = $startHour; $hour < $endHour; $hour++) {
                                $timeSlots[] = sprintf('%02d:00', $hour);
                                $timeSlots[] = sprintf('%02d:30', $hour);
                            }
                        @endphp

                        @foreach($timeSlots as $slot)
                            <button
                                class="time-slot px-1 py-2 text-xs font-medium rounded-lg transition-all
                                       bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700
                                       border border-gray-200 hover:border-blue-300"
                                data-time="{{ $slot }}"
                                data-room-id="{{ $room->id }}"
                                data-room-name="{{ $room->name }}">
                                {{ $slot }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <button class="select-all-btn text-xs py-2 px-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition"
                                data-room-id="{{ $room->id }}">
                            Pilih Semua
                        </button>
                        <button class="clear-selection-btn text-xs py-2 px-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition"
                                data-room-id="{{ $room->id }}">
                            Hapus Pilihan
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Floating Action Button - Mobile -->
        <div id="floating-action" class="hidden fixed bottom-20 left-4 right-4 bg-white rounded-xl shadow-2xl p-4 z-40 border overflow-hidden">
            <div class="flex items-center justify-between gap-2 min-w-0">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-700">
                        <span id="selected-count">0</span> slot dipilih
                    </p>
                    <p class="text-xs text-gray-500 truncate" id="selected-room-info"></p>
                </div>
                <button id="create-booking-btn"
                        class="flex-shrink-0 px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition font-medium whitespace-nowrap">
                    Buat Booking
                </button>
            </div>
        </div>
    </div>

    <!-- Booking Modal - Mobile Optimized -->
    <div id="booking-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-end sm:items-center justify-center">
        <div class="bg-white rounded-t-2xl sm:rounded-xl w-full sm:max-w-md max-h-[85vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b px-4 py-3 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Formulir Peminjaman</h3>
                <button id="close-modal-btn" class="p-1 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto">
                <form id="booking-form" method="POST" action="{{ route('bookings.store') }}" class="p-4 space-y-4">
                    @csrf
                    <input type="hidden" name="room_id" id="modal-room-id">
                    <input type="hidden" name="booking_date" id="modal-booking-date">
                    <input type="hidden" name="start_time" id="modal-start-time">
                    <input type="hidden" name="end_time" id="modal-end-time">

                    <!-- Summary -->
                    <div class="bg-blue-50 rounded-lg p-3">
                        <h4 class="font-medium text-sm text-blue-900 mb-2">Ringkasan Booking</h4>
                        <div class="space-y-1 text-xs text-blue-700">
                            <p>📍 <span id="modal-room-name" class="font-medium"></span></p>
                            <p>📅 <span id="modal-date-display" class="font-medium"></span></p>
                            <p>⏰ <span id="modal-time-display" class="font-medium"></span></p>
                            <p>⏱️ <span id="modal-duration" class="font-medium"></span></p>
                        </div>
                    </div>

                    <!-- Guest Info -->
                    <div>
                        <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="guest_name"
                            id="guest_name"
                            required
                            placeholder="Masukkan nama lengkap"
                            class="w-full text-sm rounded-lg border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label for="guest_phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor Telepon/WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="guest_phone"
                            id="guest_phone"
                            required
                            placeholder="08xx atau 628xx"
                            pattern="^(0|62)[0-9]{9,13}$"
                            class="w-full text-sm rounded-lg border-gray-300 shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">Format: 08xxxxxxxxx atau 628xxxxxxxxx</p>
                    </div>

                    <!-- Activity Field -->
                    <div>
                        <label for="activity" class="block text-sm font-medium text-gray-700 mb-1">
                            Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="activity"
                            id="activity"
                            rows="3"
                            required
                            placeholder="Deskripsikan kegiatan..."
                            class="w-full text-sm rounded-lg border-gray-300 shadow-sm"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <button type="button" id="cancel-modal-btn"
                                class="py-2.5 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition font-medium">
                            Batal
                        </button>
                        <button type="submit"
                                class="py-2.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition font-medium">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Time Filter Modal -->
    <div id="time-filter-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-end sm:items-center justify-center">
        <div class="bg-white rounded-t-2xl sm:rounded-xl w-full sm:max-w-sm">
            <div class="sticky top-0 bg-white border-b px-4 py-3 flex items-center justify-between rounded-t-2xl sm:rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Filter Jam</h3>
                <button id="close-time-filter-btn" class="p-1 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-4 space-y-4">
                <p class="text-sm text-gray-600">Pilih range jam yang ingin ditampilkan:</p>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Jam:</label>
                    <select id="filter-start-time" class="w-full text-sm rounded-lg border-gray-300 shadow-sm">
                        @for($h = 5; $h <= 22; $h++)
                            <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                            <option value="{{ sprintf('%02d:30', $h) }}">{{ sprintf('%02d:30', $h) }}</option>
                        @endfor
                        <option value="23:00">23:00</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Jam:</label>
                    <select id="filter-end-time" class="w-full text-sm rounded-lg border-gray-300 shadow-sm">
                        @for($h = 5; $h <= 22; $h++)
                            <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                            <option value="{{ sprintf('%02d:30', $h) }}">{{ sprintf('%02d:30', $h) }}</option>
                        @endfor
                        <option value="23:00" selected>23:00</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-2">
                    <button id="reset-time-filter-btn"
                            class="py-2.5 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition font-medium">
                        Reset Filter
                    </button>
                    <button id="apply-time-filter-btn"
                            class="py-2.5 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition font-medium">
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- WhatsApp Modal - Mobile Optimized -->
    <div id="wa-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-end sm:items-center justify-center">
        <div class="bg-white rounded-t-2xl sm:rounded-xl w-full sm:max-w-sm">
            <div class="p-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Info Peminjam</h3>
                </div>

                <div class="bg-gray-50 rounded-lg p-3 mb-4 space-y-2">
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Nama</p>
                        <p class="text-sm font-medium text-gray-900" id="wa-borrower-name"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Kegiatan</p>
                        <p class="text-sm text-gray-700" id="wa-activity"></p>
                    </div>
                </div>

                <div id="no-wa-message" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                    <p class="text-xs text-yellow-700">⚠️ Nomor WhatsApp tidak tersedia</p>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button id="close-wa-modal"
                            class="py-2.5 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition font-medium">
                        Tutup
                    </button>
                    <a id="wa-link" href="#" target="_blank"
                       class="py-2.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition font-medium text-center">
                        Hubungi WA
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Same JavaScript as before but with mobile optimizations
        $(document).ready(function() {
            let selectedSlots = new Map();
            let existingBookings = new Map();
            let timeFilter = { start: null, end: null };

            initializePage();

            function initializePage() {
                refreshSchedule();

                // Event bindings
                $('#booking-date').on('change', refreshSchedule);
                $('#room-filter').on('change', filterRooms);
                $('#refresh-btn').on('click', refreshSchedule);

                // Time slot clicks
                $(document).on('click', '.time-slot:not(.booked)', function(e) {
                    e.preventDefault();
                    toggleTimeSlot($(this));
                });

                // Booked slot clicks
                $(document).on('click', '.time-slot.booked', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const bookingDataStr = $(this).attr('data-booking');
                    const roomName = $(this).data('room-name');
                    if (bookingDataStr) {
                        try {
                            const bookingData = JSON.parse(bookingDataStr);
                            showWaContact(bookingData, roomName);
                        } catch (err) {
                            console.error('Error:', err);
                        }
                    }
                });

                // Quick actions
                $(document).on('click', '.select-all-btn', function() {
                    selectAllAvailable(parseInt($(this).data('room-id')));
                });

                $(document).on('click', '.clear-selection-btn', function() {
                    clearSelection(parseInt($(this).data('room-id')));
                });

                // Modal controls
                $('#create-booking-btn').on('click', openBookingModal);
                $('#close-modal-btn, #cancel-modal-btn').on('click', closeBookingModal);
                $('#close-wa-modal').on('click', closeWaModal);

                // Time filter controls
                $('#filter-time-btn').on('click', openTimeFilterModal);
                $('#close-time-filter-btn').on('click', closeTimeFilterModal);
                $('#reset-time-filter-btn').on('click', resetTimeFilter);
                $('#apply-time-filter-btn').on('click', applyTimeFilter);

                // Form submission
                $('#booking-form').on('submit', function(e) {
                    const activity = $('#activity').val().trim();
                    if (!activity) {
                        e.preventDefault();
                        alert('Silakan isi deskripsi kegiatan');
                        $('#activity').focus();
                        return false;
                    }
                    $(this).find('button[type="submit"]').prop('disabled', true).text('Memproses...');
                });
            }

            function filterRooms() {
                const filterId = $('#room-filter').val();
                $('.room-card').each(function() {
                    $(this).toggle(!filterId || $(this).data('room-id') == filterId);
                });
            }

            async function refreshSchedule() {
                const date = $('#booking-date').val();
                selectedSlots.clear();
                updateFloatingAction();

                const roomIds = [];
                $('.room-card').each(function() {
                    roomIds.push($(this).data('room-id'));
                });

                for (const roomId of roomIds) {
                    await fetchRoomBookings(roomId, date);
                }
            }

            async function fetchRoomBookings(roomId, date) {
                try {
                    const response = await $.ajax({
                        url: '{{ route("bookings.check-availability") }}',
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        data: { room_id: roomId, date: date }
                    });
                    existingBookings.set(roomId, response.bookings || []);
                    updateRoomSlots(roomId);
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            function updateRoomSlots(roomId) {
                const bookings = existingBookings.get(roomId) || [];
                const $container = $(`.time-slots-container[data-room-id="${roomId}"]`);

                $container.find('.time-slot').each(function() {
                    const $slot = $(this);
                    const slotTime = $slot.data('time') + ':00';
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
                    $slot.removeClass('booked selected bg-red-100 text-red-700 bg-blue-600 text-white ring-2 ring-blue-400');
                    $slot.removeAttr('data-booking title').prop('disabled', false);

                    if (isBooked) {
                        $slot.addClass('booked bg-red-100 text-red-700 border-red-200');
                        $slot.attr('data-booking', JSON.stringify(bookingInfo));
                        $slot.html($slot.data('time') + ' 📞');
                        $slot.attr('title', `${bookingInfo.contact_name || 'Unknown'}`);
                    } else if (selectedSlots.get(roomId)?.has($slot.data('time'))) {
                        $slot.addClass('selected bg-blue-600 text-white ring-2 ring-blue-400');
                        $slot.html($slot.data('time'));
                    } else {
                        $slot.addClass('bg-gray-50 hover:bg-blue-50 text-gray-700');
                        $slot.html($slot.data('time'));
                    }

                    // Apply time filter
                    if (timeFilter.start || timeFilter.end) {
                        const slotTime = $slot.data('time');
                        if (timeFilter.start && slotTime < timeFilter.start) {
                            $slot.hide();
                        } else if (timeFilter.end && slotTime >= timeFilter.end) {
                            $slot.hide();
                        } else {
                            $slot.show();
                        }
                    }
                });
            }

            function isTimeInRange(time, startTime, endTime) {
                return time >= startTime && time < endTime;
            }

            function toggleTimeSlot($button) {
                const roomId = parseInt($button.data('room-id'));
                const time = $button.data('time');

                if (!selectedSlots.has(roomId)) {
                    selectedSlots.set(roomId, new Set());
                }

                const roomSlots = selectedSlots.get(roomId);

                if (roomSlots.has(time)) {
                    roomSlots.delete(time);
                } else {
                    // Clear other rooms
                    for (const [otherRoomId, slots] of selectedSlots) {
                        if (otherRoomId !== roomId && slots.size > 0) {
                            if (!confirm('Hapus pilihan di ruangan lain?')) return;
                            selectedSlots.clear();
                            selectedSlots.set(roomId, new Set());
                            break;
                        }
                    }
                    roomSlots.add(time);
                }

                updateRoomSlots(roomId);
                updateFloatingAction();
            }

            function selectAllAvailable(roomId) {
                const bookings = existingBookings.get(roomId) || [];
                const availableSlots = [];

                $(`.time-slots-container[data-room-id="${roomId}"] .time-slot`).each(function() {
                    const slotTime = $(this).data('time') + ':00';
                    let isBooked = false;

                    for (const booking of bookings) {
                        if (isTimeInRange(slotTime, booking.start_time, booking.end_time)) {
                            isBooked = true;
                            break;
                        }
                    }

                    if (!isBooked) availableSlots.push($(this).data('time'));
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
                let totalSelected = 0;
                let selectedRoomName = '';

                for (const [roomId, slots] of selectedSlots) {
                    if (slots.size > 0) {
                        totalSelected = slots.size;
                        selectedRoomName = $(`.room-card[data-room-id="${roomId}"] h3`).text();
                        break;
                    }
                }

                if (totalSelected > 0) {
                    $('#selected-count').text(totalSelected);
                    $('#selected-room-info').text(selectedRoomName);
                    $('#floating-action').removeClass('hidden');
                } else {
                    $('#floating-action').addClass('hidden');
                }
            }

            function openBookingModal() {
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

                if (!checkConsecutive(selectedTimes)) {
                    alert('Slot waktu harus berurutan');
                    return;
                }

                const roomName = $(`.room-card[data-room-id="${selectedRoomId}"] h3`).text();
                const date = $('#booking-date').val();

                const startTime = selectedTimes[0];
                const endTimeHour = parseInt(selectedTimes[selectedTimes.length - 1].split(':')[0]);
                const endTimeMin = parseInt(selectedTimes[selectedTimes.length - 1].split(':')[1]);
                const endTime = `${String(endTimeMin === 30 ? endTimeHour + 1 : endTimeHour).padStart(2, '0')}:${endTimeMin === 30 ? '00' : '30'}`;

                const duration = selectedTimes.length * 0.5;
                const durationText = duration >= 1 ? `${duration} jam` : '30 menit';

                $('#modal-room-id').val(selectedRoomId);
                $('#modal-room-name').text(roomName);
                $('#modal-booking-date').val(date);
                $('#modal-date-display').text(formatDate(date));
                $('#modal-start-time').val(startTime);
                $('#modal-end-time').val(endTime);
                $('#modal-time-display').text(`${startTime} - ${endTime}`);
                $('#modal-duration').text(durationText);

                $('#booking-modal').removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }

            function closeBookingModal() {
                $('#booking-modal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
                $('#booking-form')[0].reset();
            }

            function checkConsecutive(times) {
                if (times.length === 1) return true;
                for (let i = 1; i < times.length; i++) {
                    const prev = timeToMinutes(times[i - 1]);
                    const curr = timeToMinutes(times[i]);
                    if (curr - prev !== 30) return false;
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

            function showWaContact(booking, roomName) {
                const bookingDate = $('#booking-date').val();

                $('#wa-borrower-name').text(booking.contact_name || 'Tidak diketahui');
                $('#wa-activity').text(booking.activity || 'Tidak ada keterangan');

                const startTime = booking.start_time?.substr(0, 5) || '';
                const endTime = booking.end_time?.substr(0, 5) || '';

                if (booking.contact_phone) {
                    const message = `Shalom, permisi saya mau berbicara mengenai peminjaman ruang ${roomName} pada ${formatDate(bookingDate)} di jam ${startTime}-${endTime}.`;
                    const waNumber = booking.contact_phone;
                    $('#wa-link').attr('href', `https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`);
                    $('#wa-link').removeClass('hidden');
                    $('#no-wa-message').addClass('hidden');
                } else {
                    $('#wa-link').addClass('hidden');
                    $('#no-wa-message').removeClass('hidden');
                }

                $('#wa-modal').removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }

            function openTimeFilterModal() {
                $('#time-filter-modal').removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }

            function closeTimeFilterModal() {
                $('#time-filter-modal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }

            function applyTimeFilter() {
                const startTime = $('#filter-start-time').val();
                const endTime = $('#filter-end-time').val();

                if (startTime >= endTime) {
                    alert('Jam mulai harus lebih kecil dari jam selesai');
                    return;
                }

                timeFilter.start = startTime;
                timeFilter.end = endTime;

                // Update all room slots
                $('.room-card').each(function() {
                    const roomId = $(this).data('room-id');
                    updateRoomSlots(roomId);
                });

                closeTimeFilterModal();
            }

            function resetTimeFilter() {
                timeFilter.start = null;
                timeFilter.end = null;
                $('#filter-start-time').val('05:00');
                $('#filter-end-time').val('23:00');

                // Show all slots
                $('.time-slot').show();

                closeTimeFilterModal();
            }

            function closeWaModal() {
                $('#wa-modal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>