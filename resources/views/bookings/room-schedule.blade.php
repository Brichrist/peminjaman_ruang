<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Jadwal & Peminjaman Ruangan
        </h2>
        <p class="text-blue-100 text-sm mt-1 hidden sm:block">Jadwalkan peminjaman ruangan Anda</p>
    </x-slot>

    @include('components.toast-notification')

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <!-- Date & Filter Section -->
            <div class="bg-white rounded-xl shadow-sm mb-4 sm:mb-6 p-4">
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch sm:items-end">
                    <!-- Date Picker -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Pilih Tanggal</label>
                        <input type="date" id="booking-date"
                               class="w-full text-sm sm:text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ date('Y-m-d') }}"
                               min="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Room Filter -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Filter Ruangan</label>
                        <select id="room-filter" class="w-full text-sm sm:text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Ruangan</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 sm:flex-shrink-0">
                        <button id="filter-time-btn"
                                class="flex-1 sm:flex-none px-4 sm:px-6 py-2.5 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="hidden sm:inline">Filter Jam</span>
                            <span class="sm:hidden">Jam</span>
                        </button>
                        <button id="refresh-btn"
                                class="flex-1 sm:flex-none px-4 sm:px-6 py-2.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>Refresh</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Information Note -->
            <div class="mb-4 sm:mb-6 bg-blue-50 border-l-4 border-blue-500 p-3 sm:p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500 mr-2 sm:mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-xs sm:text-sm text-blue-700">
                        <strong>Catatan:</strong> Silahkan booking ruangan dari waktu mulai hingga waktu selesai acara
                    </p>
                </div>
            </div>

            <!-- Room Cards Grid -->
            <div id="room-grid" class="grid gap-4 sm:gap-6 lg:grid-cols-2 xl:grid-cols-3">
                @foreach($rooms as $room)
                <div class="room-card bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-200" data-room-id="{{ $room->id }}">
                    <!-- Room Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-3 sm:p-4">
                        <h3 class="text-base sm:text-lg font-semibold truncate">{{ $room->name }}</h3>
                        <div class="flex items-center gap-3 sm:gap-4 mt-1 sm:mt-2 text-xs sm:text-sm">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="hidden sm:inline">Kapasitas:</span> {{ $room->capacity }} <span class="sm:hidden">org</span><span class="hidden sm:inline">orang</span>
                            </span>
                            @if($room->status == 'available')
                                <span class="bg-green-100 text-green-800 px-2 py-0.5 sm:py-1 rounded-full text-xs font-medium">Tersedia</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-0.5 sm:py-1 rounded-full text-xs font-medium">Tidak Tersedia</span>
                            @endif
                        </div>
                    </div>

                    <!-- Room Content -->
                    <div class="p-3 sm:p-4">
                        <p class="text-xs sm:text-sm text-gray-600 mb-3 line-clamp-2">{{ $room->description }}</p>

                        <!-- Time Slots Grid -->
                        <div class="grid grid-cols-3 gap-1.5 sm:gap-2 time-slots-container" data-room-id="{{ $room->id }}">
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
                                @php
                                    $parts = explode(':', $slot);
                                    $slotHour = (int)$parts[0];
                                    $slotMin = (int)$parts[1];

                                    // Calculate end time (+30 minutes)
                                    $endMin = $slotMin + 30;
                                    $endHourCalc = $slotHour;
                                    if ($endMin >= 60) {
                                        $endMin = 0;
                                        $endHourCalc++;
                                    }

                                    $displayStart = sprintf('%02d.%02d', $slotHour, $slotMin);
                                    $displayEnd = sprintf('%02d.%02d', $endHourCalc, $endMin);
                                    $displayText = $displayStart . ' - ' . $displayEnd;
                                @endphp
                                <button
                                    class="time-slot px-1 sm:px-2 py-2 text-xs font-medium rounded-lg transition-all duration-200
                                           bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700 border border-gray-200 hover:border-blue-300"
                                    data-time="{{ $slot }}"
                                    data-display="{{ $displayText }}"
                                    data-room-id="{{ $room->id }}"
                                    data-room-name="{{ $room->name }}">
                                    {{ $displayText }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Quick Action Buttons -->
                        <div class="mt-3 sm:mt-4 flex gap-2">
                            <button class="select-all-btn flex-1 text-xs sm:text-sm py-2 px-2 sm:px-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-200"
                                    data-room-id="{{ $room->id }}">
                                <span class="hidden sm:inline">Pilih Semua Tersedia</span>
                                <span class="sm:hidden">Pilih Semua</span>
                            </button>
                            <button class="clear-selection-btn flex-1 text-xs sm:text-sm py-2 px-2 sm:px-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-200"
                                    data-room-id="{{ $room->id }}">
                                Hapus Pilihan
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Floating Action Button - Responsive -->
        <div id="floating-action" class="hidden fixed bottom-20 sm:bottom-6 left-4 right-4 sm:left-auto sm:right-6 bg-white rounded-xl shadow-2xl p-4 z-40 border sm:min-w-[280px]">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-700">
                        <span id="selected-count">0</span> slot terpilih
                    </p>
                    <p class="text-xs text-gray-500 truncate" id="selected-room-info"></p>
                </div>
                <button id="create-booking-btn"
                        class="flex-shrink-0 px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition duration-200 font-medium whitespace-nowrap">
                    Buat Booking
                </button>
            </div>
        </div>
    </div>

    <!-- Booking Modal - Responsive -->
    <div id="booking-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white rounded-t-2xl sm:rounded-xl w-full sm:max-w-md max-h-[90vh] overflow-hidden">
            <div class="sticky top-0 bg-white border-b px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Formulir Peminjaman</h3>
                    <button id="close-modal-btn" class="text-gray-400 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="overflow-y-auto">
                <form id="booking-form" method="POST" action="{{ route('bookings.store') }}" class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                    @csrf
                    <input type="hidden" name="room_id" id="modal-room-id">
                    <input type="hidden" name="booking_date" id="modal-booking-date">
                    <input type="hidden" name="start_time" id="modal-start-time">
                    <input type="hidden" name="end_time" id="modal-end-time">

                    <!-- Summary -->
                    <div class="bg-blue-50 rounded-lg p-3 sm:p-4">
                        <h4 class="font-medium text-sm sm:text-base text-blue-900 mb-2">Ringkasan Peminjaman</h4>
                        <div class="space-y-1 text-xs sm:text-sm text-blue-700">
                            <p>Ruangan: <span id="modal-room-name" class="font-medium"></span></p>
                            <p>Tanggal: <span id="modal-date-display" class="font-medium"></span></p>
                            <p>Waktu: <span id="modal-time-display" class="font-medium"></span></p>
                            <p>Durasi: <span id="modal-duration" class="font-medium"></span></p>
                        </div>
                    </div>

                    <!-- Guest Information -->
                    <div>
                        <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="guest_name"
                            id="guest_name"
                            required
                            placeholder="Masukkan nama lengkap"
                            class="w-full text-sm sm:text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="guest_phone" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Nomor Telepon/WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="guest_phone"
                            id="guest_phone"
                            required
                            placeholder="08xx atau 628xx"
                            pattern="^(0|62)[0-9]{9,13}$"
                            class="w-full text-sm sm:text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Format: 08xxxxxxxxx atau 628xxxxxxxxx</p>
                    </div>

                    <!-- Activity Field -->
                    <div>
                        <label for="activity" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="activity"
                            id="activity"
                            rows="3"
                            required
                            placeholder="Deskripsikan kegiatan yang akan dilakukan..."
                            class="w-full text-sm sm:text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 sm:gap-3 pt-2">
                        <button type="button" id="cancel-modal-btn"
                                class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- WhatsApp Contact Modal - Responsive -->
    <div id="wa-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white rounded-t-2xl sm:rounded-xl w-full sm:max-w-md shadow-2xl">
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-2xl sm:rounded-t-xl">
                <h3 class="text-lg sm:text-xl font-semibold flex items-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824z"/>
                    </svg>
                    Informasi Peminjam
                </h3>
            </div>

            <div class="p-4 sm:p-6">
                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 mb-4 space-y-2 sm:space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-0.5 sm:mb-1">Nama Peminjam</p>
                        <p class="text-sm sm:text-base text-gray-900 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span id="wa-borrower-name"></span>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-0.5 sm:mb-1">Kegiatan</p>
                        <p class="text-sm sm:text-base text-gray-900 flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span id="wa-activity"></span>
                        </p>
                    </div>
                </div>

                <div id="no-wa-message" class="hidden bg-yellow-50 border-l-4 border-yellow-400 p-3 sm:p-4 mb-4">
                    <div class="flex">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-xs sm:text-sm text-yellow-700">
                            Nomor WhatsApp tidak tersedia untuk peminjam ini
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button id="close-wa-modal"
                            class="flex-1 px-4 py-2.5 sm:py-3 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                        Tutup
                    </button>
                    <a id="wa-link" href="#" target="_blank"
                       class="flex-1 px-4 py-2.5 sm:py-3 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition duration-200 text-center flex items-center justify-center font-medium">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824z"/>
                        </svg>
                        Hubungi
                    </a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <form id="cancel-booking-form" method="POST" action="" class="flex-1">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan peminjaman ini?')"
                                        class="w-full px-4 py-2.5 sm:py-3 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition duration-200 font-medium flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Time Filter Modal - Responsive -->
    <div id="time-filter-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white rounded-t-2xl sm:rounded-xl w-full sm:max-w-md shadow-2xl">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-2xl sm:rounded-t-xl">
                <h3 class="text-lg sm:text-xl font-semibold flex items-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Filter Waktu
                </h3>
            </div>

            <div class="p-4 sm:p-6">
                <p class="text-xs sm:text-sm text-gray-600 mb-4">Pilih rentang waktu yang ingin ditampilkan</p>

                <div class="space-y-4 mb-6">
                    <div>
                        <label for="filter-start-time" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Dari Jam
                        </label>
                        <select id="filter-start-time" class="w-full text-sm sm:text-base rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            <option value="">Pilih jam mulai</option>
                            @php
                                $filterSlots = [];
                                for ($hour = 5; $hour < 23; $hour++) {
                                    $filterSlots[] = sprintf('%02d:00', $hour);
                                    $filterSlots[] = sprintf('%02d:30', $hour);
                                }
                            @endphp
                            @foreach($filterSlots as $slot)
                                <option value="{{ $slot }}">{{ $slot }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter-end-time" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                            Sampai Jam
                        </label>
                        <select id="filter-end-time" class="w-full text-sm sm:text-base rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            <option value="">Pilih jam selesai</option>
                            @foreach($filterSlots as $slot)
                                <option value="{{ $slot }}">{{ $slot }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-2 sm:gap-3">
                    <button id="reset-time-filter"
                            class="flex-1 px-4 py-2.5 sm:py-3 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                        Reset Filter
                    </button>
                    <button id="apply-time-filter"
                            class="flex-1 px-4 py-2.5 sm:py-3 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition duration-200 font-medium">
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            let selectedSlots = new Map();
            let existingBookings = new Map();
            let timeFilter = { start: null, end: null };

            // Initialize page
            initializePage();

            function initializePage() {
                // Load initial schedule
                refreshSchedule();

                // Bind events using jQuery
                $('#booking-date').on('change', refreshSchedule);
                $('#room-filter').on('change', filterRooms);
                $('#refresh-btn').on('click', refreshSchedule);
                $('#filter-time-btn').on('click', openTimeFilterModal);
                $('#apply-time-filter').on('click', applyTimeFilter);
                $('#reset-time-filter').on('click', resetTimeFilter);

                // Time slot selection using event delegation
                $(document).on('click', '.time-slot:not(.booked)', function(e) {
                    e.preventDefault();
                    toggleTimeSlot($(this));
                });

                // Booked slot click - show WhatsApp contact
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
                            console.error('Error parsing booking data:', err);
                        }
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

                // Close modals on backdrop click
                $('#booking-modal, #wa-modal, #time-filter-modal').on('click', function(e) {
                    if (e.target === this) {
                        $(this).addClass('hidden');
                        $('body').removeClass('overflow-hidden');
                    }
                });

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

                    // Check if slot should be filtered out
                    const slotTimeOnly = $slot.data('time');
                    if (timeFilter.start || timeFilter.end) {
                        const slotMinutes = timeToMinutes(slotTimeOnly);
                        const startMinutes = timeFilter.start ? timeToMinutes(timeFilter.start) : 0;
                        const endMinutes = timeFilter.end ? timeToMinutes(timeFilter.end) : 24 * 60;

                        if (slotMinutes < startMinutes || slotMinutes >= endMinutes) {
                            $slot.hide();
                            return;
                        }
                    }

                    $slot.show();

                    for (const booking of bookings) {
                        if (isTimeInRange(slotTime, booking.start_time, booking.end_time)) {
                            isBooked = true;
                            bookingInfo = booking;
                            break;
                        }
                    }

                    // Reset classes and remove old data
                    $slot.removeClass('booked selected bg-red-100 border-red-200 text-red-700 bg-blue-600 bg-blue-100 text-white text-blue-800 ring-2 ring-blue-400 border-blue-400 bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700 border-gray-200 hover:border-blue-300');
                    $slot.removeAttr('data-booking');
                    $slot.removeAttr('title');
                    $slot.prop('disabled', false);

                    const displayText = $slot.data('display') || $slot.data('time');

                    if (isBooked) {
                        $slot.addClass('booked bg-red-100 text-red-700 border-red-200 cursor-pointer hover:bg-red-200');
                        $slot.attr('data-booking', JSON.stringify(bookingInfo));
                        $slot.prop('disabled', false);
                        $slot.html(displayText + ' <span class="text-xs">📞</span>');
                        $slot.attr('title', `Dipesan oleh: ${bookingInfo.contact_name || 'Unknown'} - Klik untuk hubungi`);
                    } else if (selectedSlots.get(roomId)?.has($slot.data('time'))) {
                        $slot.addClass('selected bg-blue-100 text-blue-800 ring-2 ring-blue-400 border-blue-400');
                        $slot.html(displayText);
                    } else {
                        $slot.addClass('bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700 border-gray-200 hover:border-blue-300');
                        $slot.html(displayText);
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
                const bookingDate = $('#booking-date').val();

                $('#wa-borrower-name').text(booking.contact_name || 'Tidak diketahui');
                $('#wa-activity').text(booking.activity || 'Tidak ada keterangan');

                const startTime = booking.start_time ? booking.start_time.substr(0, 5) : '';
                const endTime = booking.end_time ? booking.end_time.substr(0, 5) : '';

                if (booking.contact_phone) {
                    const message = `Shalom, permisi saya mau berbicara mengenai peminjaman ruang ${roomName} pada tanggal ${formatDate(bookingDate)} di jam ${startTime} - ${endTime}.`;
                    const waNumber = booking.contact_phone;
                    const waLink = `https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`;

                    $('#wa-link').attr('href', waLink);
                    $('#wa-link').removeClass('hidden');
                    $('#no-wa-message').addClass('hidden');
                } else {
                    $('#wa-link').addClass('hidden');
                    $('#no-wa-message').removeClass('hidden');
                }

                if (booking.id) {
                    const cancelUrl = `/admin/bookings/${booking.id}/cancel`;
                    $('#cancel-booking-form').attr('action', cancelUrl);
                }

                $('#wa-modal').removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }

            function closeWaModal() {
                $('#wa-modal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }

            function openTimeFilterModal() {
                if (timeFilter.start) {
                    $('#filter-start-time').val(timeFilter.start);
                }
                if (timeFilter.end) {
                    $('#filter-end-time').val(timeFilter.end);
                }
                $('#time-filter-modal').removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }

            function applyTimeFilter() {
                const startTime = $('#filter-start-time').val();
                const endTime = $('#filter-end-time').val();

                if (startTime && endTime) {
                    const startMinutes = timeToMinutes(startTime);
                    const endMinutes = timeToMinutes(endTime);

                    if (endMinutes <= startMinutes) {
                        alert('Jam selesai harus lebih besar dari jam mulai');
                        return;
                    }
                }

                timeFilter.start = startTime || null;
                timeFilter.end = endTime || null;

                $('#time-filter-modal').addClass('hidden');
                $('body').removeClass('overflow-hidden');

                const roomIds = [];
                $('.room-card').each(function() {
                    roomIds.push($(this).data('room-id'));
                });

                roomIds.forEach(roomId => {
                    updateRoomSlots(roomId);
                });
            }

            function resetTimeFilter() {
                timeFilter.start = null;
                timeFilter.end = null;

                $('#filter-start-time').val('');
                $('#filter-end-time').val('');

                $('#time-filter-modal').addClass('hidden');
                $('body').removeClass('overflow-hidden');

                const roomIds = [];
                $('.room-card').each(function() {
                    roomIds.push($(this).data('room-id'));
                });

                roomIds.forEach(roomId => {
                    updateRoomSlots(roomId);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
