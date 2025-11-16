<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Jadwal & Peminjaman Ruangan
        </h2>
        <p class="text-blue-100 text-sm mt-1">jadwalkan peminjaman ruangan Anda</p>
    </x-slot>

    @include('components.toast-notification')

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
                        <button id="refresh-btn"
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
                                $startHour = 5;
                                $endHour = 23;

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
                                    data-room-name="{{ $room->name }}">
                                    {{ $slot }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Quick Action Buttons -->
                        <div class="mt-4 flex gap-2">
                            <button class="select-all-btn flex-1 text-sm py-2 px-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-200"
                                    data-room-id="{{ $room->id }}">
                                Pilih Semua Tersedia
                            </button>
                            <button class="clear-selection-btn flex-1 text-sm py-2 px-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-200"
                                    data-room-id="{{ $room->id }}">
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
                <button id="create-booking-btn"
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
                    <button id="close-modal-btn" class="text-gray-400 hover:text-gray-600">
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

                <!-- Guest Information -->
                <div class="mb-6">
                    <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="guest_name"
                        id="guest_name"
                        required
                        placeholder="Masukkan nama lengkap Anda"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mb-6">
                    <label for="guest_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Telepon/WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="guest_phone"
                        id="guest_phone"
                        required
                        placeholder="08xx atau 628xx"
                        pattern="^(0|62)[0-9]{9,13}$"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Format: 08xxxxxxxxx atau 628xxxxxxxxx</p>
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

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="button" id="cancel-modal-btn"
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
        <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-t-xl">
                <h3 class="text-xl font-semibold flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824z"/>
                    </svg>
                    Informasi Peminjam
                </h3>
            </div>

            <div class="p-6">
                <div class="bg-gray-50 rounded-lg p-4 mb-4 space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Nama Peminjam</p>
                        <p class="text-gray-900 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span id="wa-borrower-name"></span>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Kegiatan</p>
                        <p class="text-gray-900 flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span id="wa-activity"></span>
                        </p>
                    </div>
                </div>

                <div id="no-wa-message" class="hidden bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm text-yellow-700">
                            Nomor WhatsApp tidak tersedia untuk peminjam ini
                        </p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button id="close-wa-modal"
                            class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                        Tutup
                    </button>
                    <a id="wa-link" href="#" target="_blank"
                       class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 text-center flex items-center justify-center font-medium">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824z"/>
                        </svg>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            let selectedSlots = new Map();
            let existingBookings = new Map();

            // Initialize page
            initializePage();

            function initializePage() {
                // Load initial schedule
                refreshSchedule();

                // Bind events using jQuery
                $('#booking-date').on('change', refreshSchedule);
                $('#room-filter').on('change', filterRooms);
                $('#refresh-btn').on('click', refreshSchedule);

                // Time slot selection using event delegation
                $(document).on('click', '.time-slot:not(.booked)', function(e) {
                    e.preventDefault();
                    toggleTimeSlot($(this));
                });

                // Booked slot click - show WhatsApp contact
                $(document).on('click', '.time-slot.booked', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Get booking data from attribute
                    const bookingDataStr = $(this).attr('data-booking');
                    const roomName = $(this).data('room-name');

                    console.log('Clicked booked slot, data:', bookingDataStr); // Debug

                    if (bookingDataStr) {
                        try {
                            const bookingData = JSON.parse(bookingDataStr);
                            showWaContact(bookingData, roomName);
                        } catch (err) {
                            console.error('Error parsing booking data:', err);
                            alert('Error loading booking information');
                        }
                    } else {
                        console.warn('No booking data found for this slot');
                    }
                });

                // Quick action buttons
                $(document).on('click', '.select-all-btn', function() {
                    const roomId = parseInt($(this).data('room-id'));
                    selectAllAvailable(roomId);
                });

                $(document).on('click', '.clear-selection-btn', function() {
                    const roomId = parseInt($(this).data('room-id'));
                    clearSelection(roomId);
                });

                // Modal buttons
                $('#create-booking-btn').on('click', openBookingModal);
                $('#close-modal-btn, #cancel-modal-btn').on('click', closeBookingModal);
                $('#close-wa-modal').on('click', closeWaModal);

                // Form submission with validation
                $('#booking-form').on('submit', function(e) {
                    const activity = $('#activity').val().trim();

                    if (!activity) {
                        e.preventDefault();
                        alert('Silakan isi deskripsi kegiatan');
                        $('#activity').focus();
                        return false;
                    }

                    // Show loading state
                    $(this).find('button[type="submit"]')
                        .prop('disabled', true)
                        .html('<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>Memproses...');
                });
            }

            function filterRooms() {
                const filterId = $('#room-filter').val();
                $('.room-card').each(function() {
                    if (filterId === '' || $(this).data('room-id') == filterId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            async function refreshSchedule() {
                const date = $('#booking-date').val();
                selectedSlots.clear();
                updateFloatingAction();

                // Get all room IDs
                const roomIds = [];
                $('.room-card').each(function() {
                    roomIds.push($(this).data('room-id'));
                });

                // Fetch bookings for all rooms
                for (const roomId of roomIds) {
                    await fetchRoomBookings(roomId, date);
                }
            }

            async function fetchRoomBookings(roomId, date) {
                try {
                    const response = await $.ajax({
                        url: '{{ route("bookings.check-availability") }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            room_id: roomId,
                            date: date
                        }
                    });

                    existingBookings.set(roomId, response.bookings || []);
                    updateRoomSlots(roomId);
                } catch (error) {
                    console.error('Error fetching bookings:', error);
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

                    // Reset classes and remove old data
                    $slot.removeClass('booked selected bg-red-100 text-red-700 bg-blue-600 text-white ring-2 ring-blue-400 bg-gray-50 hover:bg-blue-50');
                    $slot.removeAttr('data-booking');
                    $slot.removeAttr('title');
                    $slot.prop('disabled', false);

                    if (isBooked) {
                        // Style for booked slots
                        $slot.addClass('booked bg-red-100 text-red-700 border-red-200 cursor-pointer hover:bg-red-100');

                        // Store booking data properly
                        $slot.attr('data-booking', JSON.stringify(bookingInfo));
                        $slot.prop('disabled', false); // Enable click for WhatsApp contact

                        // Add visual indicator and tooltip
                        $slot.html($slot.data('time') + ' <span class="text-xs">📞</span>');
                        $slot.attr('title', `Dipesan oleh: ${bookingInfo.contact_name || 'Unknown'} - Klik untuk hubungi`);
                    } else if (selectedSlots.get(roomId)?.has($slot.data('time'))) {
                        // Style for selected slots
                        $slot.addClass('selected bg-blue-600 text-white ring-2 ring-blue-400');
                        $slot.html($slot.data('time')); // Reset text
                    } else {
                        // Style for available slots
                        $slot.addClass('bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700 border-gray-200 hover:border-blue-300');
                        $slot.html($slot.data('time')); // Reset text
                    }
                });
            }

            function isTimeInRange(time, startTime, endTime) {
                // A slot is considered booked if it falls within the booking time range
                // We use >= for start and < for end to allow consecutive bookings
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
                    // Clear other room selections
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

                    if (!isBooked) {
                        availableSlots.push($(this).data('time'));
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
                let totalSelected = 0;
                for (const slots of selectedSlots.values()) {
                    totalSelected += slots.size;
                }

                if (totalSelected > 0) {
                    $('#selected-count').text(totalSelected);
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
                    alert('Slot waktu yang dipilih harus berurutan');
                    return;
                }

                const $roomCard = $(`.room-card[data-room-id="${selectedRoomId}"]`);
                const roomName = $roomCard.find('h3').text();
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
            }

            function closeBookingModal() {
                $('#booking-modal').addClass('hidden');
                $('#booking-form')[0].reset();
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

            function showWaContact(booking, roomName) {
                console.log('Booking data:', booking); // Debug

                const bookingDate = $('#booking-date').val();

                // Update modal content
                $('#wa-borrower-name').text(booking.contact_name || 'Tidak diketahui');
                $('#wa-activity').text(booking.activity || 'Tidak ada keterangan');

                // Format time
                const startTime = booking.start_time ? booking.start_time.substr(0, 5) : '';
                const endTime = booking.end_time ? booking.end_time.substr(0, 5) : '';

                // Check if WhatsApp number exists
                if (booking.contact_phone) {
                    // Format message (without sender name since public access)
                    const message = `Shalom, permisi saya mau berbicara mengenai peminjaman ruang ${roomName} pada tanggal ${formatDate(bookingDate)} di jam ${startTime} - ${endTime}.`;

                    // Use already formatted WhatsApp number from API
                    const waNumber = booking.contact_phone;
                    const waLink = `https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`;

                    $('#wa-link').attr('href', waLink);
                    $('#wa-link').removeClass('hidden');
                    $('#no-wa-message').addClass('hidden');
                } else {
                    // No WhatsApp number available
                    $('#wa-link').addClass('hidden');
                    $('#no-wa-message').removeClass('hidden');
                }

                // Show modal
                $('#wa-modal').removeClass('hidden');
            }

            function closeWaModal() {
                $('#wa-modal').addClass('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>