<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Aksi Cepat</h3>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('rooms.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Tambah Ruangan
                        </a>
                        <a href="{{ route('admin.bookings') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            Kelola Peminjaman
                        </a>
                        <a href="{{ route('admin.users') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                            Kelola Pengguna
                        </a>
                        <a href="{{ route('admin.reports') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Today's Schedule -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Jadwal Hari Ini</h3>
                        @if($todaysSchedule->count() > 0)
                            <div class="space-y-2">
                                @foreach($todaysSchedule as $schedule)
                                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                                        <div class="font-medium">{{ $schedule->room->name }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                        </div>
                                        <div class="text-sm">{{ $schedule->user->name }} - {{ $schedule->activity }}</div>
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
                        @if($recentBookings->count() > 0)
                            <div class="space-y-2">
                                @foreach($recentBookings as $booking)
                                    <div class="flex justify-between items-start py-2 border-b">
                                        <div>
                                            <div class="font-medium">{{ $booking->user->name }}</div>
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