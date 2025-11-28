<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-blue-600">{{ $totalRooms }}</div>
                        <div class="text-gray-600">Total Ruangan</div>
                        <div class="text-sm text-green-600">{{ $availableRooms }} tersedia</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-green-600">{{ $todayBookings }}</div>
                        <div class="text-gray-600">Peminjaman Hari Ini</div>
                        <div class="text-sm text-blue-600">{{ $upcomingBookings }} mendatang</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-2xl font-bold text-purple-600">{{ $totalUsers }}</div>
                        <div class="text-gray-600">Total Pengguna</div>
                        <div class="text-sm text-gray-600">Non-admin</div>
                    </div>
                </div>
            </div>

            <!-- Admin Menu Grid -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Menu Admin</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <!-- Kelola Ruangan -->
                        <a href="{{ route('rooms.index') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors border border-blue-200">
                            <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-sm font-medium text-blue-700">Kelola Ruangan</span>
                        </a>

                        <!-- Tambah Ruangan -->
                        {{-- <a href="{{ route('rooms.create') }}" class="flex flex-col items-center p-4 bg-sky-50 rounded-lg hover:bg-sky-100 transition-colors border border-sky-200">
                            <svg class="w-8 h-8 text-sky-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="text-sm font-medium text-sky-700">Tambah Ruangan</span>
                        </a> --}}

                        <!-- Lihat Jadwal & Peminjaman -->
                        <a href="{{ route('bookings.room-schedule') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors border border-green-200">
                            <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-green-700">Jadwal & Peminjaman</span>
                        </a>

                        <!-- Kelola Pengguna -->
                        <a href="{{ route('admin.users') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors border border-purple-200">
                            <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-purple-700">Kelola Pengguna</span>
                        </a>

                        <!-- Lihat Laporan -->
                        <a href="{{ route('admin.reports') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors border border-yellow-200">
                            <svg class="w-8 h-8 text-yellow-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-yellow-700">Lihat Laporan</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Today's Schedule -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Jadwal Hari Ini</h3>
                        @if ($todaysSchedule->count() > 0)
                            <div class="space-y-2">
                                @foreach ($todaysSchedule as $schedule)
                                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                                        <div class="font-medium">{{ $schedule->room->name }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                        </div>
                                        <div class="text-sm">{{ $schedule->getContactName() }} - {{ $schedule->activity }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Tidak ada peminjaman hari ini.</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Peminjaman Terbaru</h3>
                        @if ($recentBookings->count() > 0)
                            <div class="space-y-2">
                                @foreach ($recentBookings as $booking)
                                    <div class="flex justify-between items-start py-2 border-b">
                                        <div>
                                            <div class="font-medium">{{ $booking->getContactName() }}</div>
                                            <div class="text-sm text-gray-600">
                                                {{ $booking->room->name }} - {{ $booking->booking_date->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                            Disetujui
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Belum ada peminjaman.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
