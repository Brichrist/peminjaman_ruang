<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Laporan Peminjaman
        </h2>
        <p class="text-blue-100 text-sm mt-1">Laporan peminjaman ruangan per hari</p>
    </x-slot>

    <div class="py-12 min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Form -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <form method="GET" action="{{ route('admin.reports') }}" id="reportForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline-block mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tanggal Mulai
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                   value="{{ $startDate instanceof \Carbon\Carbon ? $startDate->format('Y-m-d') : $startDate }}"
                                   class="w-full px-4 py-2 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline-block mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tanggal Akhir
                            </label>
                            <input type="date" name="end_date" id="end_date"
                                   value="{{ $endDate instanceof \Carbon\Carbon ? $endDate->format('Y-m-d') : $endDate }}"
                                   class="w-full px-4 py-2 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        </div>

                        <!-- Room Filter (Multiselect) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline-block mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Pilih Ruangan
                            </label>
                            <div class="relative">
                                <button type="button" id="roomDropdownBtn"
                                        class="w-full px-4 py-2 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-left flex items-center justify-between bg-white">
                                    <span id="selectedRoomsText" class="text-gray-700">
                                        @if(empty($selectedRooms) || count($selectedRooms) == count($rooms))
                                            Semua Ruangan
                                        @else
                                            {{ count($selectedRooms) }} Ruangan Dipilih
                                        @endif
                                    </span>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div id="roomDropdown" class="hidden absolute z-10 w-full mt-2 bg-white border-2 border-gray-200 rounded-lg shadow-lg max-h-64 overflow-y-auto">
                                    <div class="p-3 border-b border-gray-200">
                                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                            <input type="checkbox" id="selectAllRooms" class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                                   {{ empty($selectedRooms) || count($selectedRooms) == count($rooms) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm font-semibold text-gray-700">Pilih Semua</span>
                                        </label>
                                    </div>
                                    <div class="p-2">
                                        @foreach($rooms as $room)
                                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                                <input type="checkbox" name="room_ids[]" value="{{ $room->id }}"
                                                       class="room-checkbox w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                                       {{ in_array($room->id, $selectedRooms) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">{{ $room->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 justify-end">
                        <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Tampilkan Laporan
                        </button>

                        <button type="button" id="downloadPdfBtn"
                                class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </button>
                    </div>
                </form>
            </div>

            <!-- Report Content -->
            @if($bookingsByDate->count() > 0)
                <div class="space-y-6">
                    @foreach($bookingsByDate as $date => $bookings)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <!-- Date Header -->
                            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                                <h3 class="text-xl font-semibold text-white flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') }}
                                </h3>
                                <p class="text-indigo-100 text-sm mt-1">{{ $bookings->count() }} peminjaman</p>
                            </div>

                            <!-- Bookings List -->
                            <div class="p-6">
                                @php
                                    $bookingsByRoom = $bookings->groupBy('room_id');
                                @endphp

                                @foreach($bookingsByRoom as $roomId => $roomBookings)
                                    <div class="mb-6 last:mb-0">
                                        <!-- Room Name -->
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center mr-3">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $roomBookings->first()->room->name }}</h4>
                                        </div>

                                        <!-- Time Slots -->
                                        <div class="space-y-2 ml-13">
                                            @foreach($roomBookings as $booking)
                                                <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-3">
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}
                                                            </span>
                                                            <span class="text-gray-900 font-medium">{{ $booking->activity }}</span>
                                                        </div>
                                                        <div class="mt-2 text-sm text-gray-600">
                                                            <span class="inline-flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                </svg>
                                                                {{ $booking->getContactName() }}
                                                            </span>
                                                            @if($booking->getContactPhone())
                                                                <span class="ml-4 inline-flex items-center">
                                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                                    </svg>
                                                                    {{ $booking->getContactPhone() }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data peminjaman</h3>
                    <p class="text-gray-500">Tidak ada peminjaman pada rentang tanggal dan ruangan yang dipilih.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for Multiselect Dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownBtn = document.getElementById('roomDropdownBtn');
            const dropdown = document.getElementById('roomDropdown');
            const selectAllCheckbox = document.getElementById('selectAllRooms');
            const roomCheckboxes = document.querySelectorAll('.room-checkbox');
            const selectedRoomsText = document.getElementById('selectedRoomsText');
            const downloadPdfBtn = document.getElementById('downloadPdfBtn');
            const reportForm = document.getElementById('reportForm');

            // Toggle dropdown
            dropdownBtn.addEventListener('click', function(e) {
                e.preventDefault();
                dropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdownBtn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });

            // Update text when checkboxes change
            function updateSelectedText() {
                const checkedCount = document.querySelectorAll('.room-checkbox:checked').length;
                const totalCount = roomCheckboxes.length;

                if (checkedCount === 0) {
                    selectedRoomsText.textContent = 'Pilih Ruangan';
                } else if (checkedCount === totalCount) {
                    selectedRoomsText.textContent = 'Semua Ruangan';
                    selectAllCheckbox.checked = true;
                } else {
                    selectedRoomsText.textContent = checkedCount + ' Ruangan Dipilih';
                    selectAllCheckbox.checked = false;
                }
            }

            // Select all functionality
            selectAllCheckbox.addEventListener('change', function() {
                roomCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedText();
            });

            // Individual checkbox change
            roomCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedText();
                });
            });

            // Download PDF button
            downloadPdfBtn.addEventListener('click', function() {
                const form = reportForm.cloneNode(true);
                form.action = '{{ route("admin.reports.download") }}';
                form.style.display = 'none';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            });

            // Initial text update
            updateSelectedText();
        });
    </script>
</x-app-layout>
