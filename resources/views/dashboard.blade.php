<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Hero Section -->
        <div class="pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">
                        Selamat datang, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-lg text-gray-600">
                        Sistem Peminjaman Ruangan NICC
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        @php
            $todayBookings = App\Models\Booking::where('booking_date', today())->where('status', 'approved')->count();
            $myBookings = App\Models\Booking::where('user_id', auth()->id())
                ->where('booking_date', '>=', today())
                ->where('status', 'approved')
                ->count();
            $availableRooms = App\Models\Room::where('status', 'available')->count();
        @endphp

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Booking Hari Ini</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $todayBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Booking Aktif Saya</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $myBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Ruangan Tersedia</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $availableRooms }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Actions -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Menu Utama</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Booking & Schedule -->
                <a href="{{ route('bookings.room-schedule') }}" class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="w-14 h-14 bg-blue-100 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-4 transition-colors duration-300">
                            <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-white mb-2 transition-colors duration-300">
                            Booking Ruangan
                        </h3>
                        <p class="text-sm text-gray-600 group-hover:text-white/90 transition-colors duration-300">
                            Lihat jadwal dan buat peminjaman baru dengan sistem interaktif
                        </p>
                    </div>
                </a>

                <!-- Schedule View -->
                {{-- <a href="{{ route('bookings.schedule') }}" class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-600 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="w-14 h-14 bg-purple-100 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-4 transition-colors duration-300">
                            <svg class="w-8 h-8 text-purple-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-white mb-2 transition-colors duration-300">
                            Jadwal Ruangan
                        </h3>
                        <p class="text-sm text-gray-600 group-hover:text-white/90 transition-colors duration-300">
                            Lihat jadwal peminjaman semua ruangan
                        </p>
                    </div>
                </a> --}}

                <!-- Profile Settings -->
                <a href="{{ route('profile.edit') }}" class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-600 to-gray-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="w-14 h-14 bg-gray-100 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-4 transition-colors duration-300">
                            <svg class="w-8 h-8 text-gray-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-white mb-2 transition-colors duration-300">
                            Profil Saya
                        </h3>
                        <p class="text-sm text-gray-600 group-hover:text-white/90 transition-colors duration-300">
                            Update informasi dan nomor WhatsApp
                        </p>
                    </div>
                </a>

                @if (auth()->user()?->isAdmin() ?? null)
                    <!-- Admin Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                        <div class="absolute inset-0 bg-gradient-to-br from-red-600 to-red-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative p-6">
                            <div class="w-14 h-14 bg-red-100 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-4 transition-colors duration-300">
                                <svg class="w-8 h-8 text-red-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-white mb-2 transition-colors duration-300">
                                Admin Panel
                            </h3>
                            <p class="text-sm text-gray-600 group-hover:text-white/90 transition-colors duration-300">
                                Kelola sistem dan monitoring
                            </p>
                        </div>
                    </a>

                    <!-- Room Management -->
                    <a href="{{ route('rooms.index') }}" class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                        <div class="absolute inset-0 bg-gradient-to-br from-yellow-600 to-yellow-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative p-6">
                            <div class="w-14 h-14 bg-yellow-100 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-4 transition-colors duration-300">
                                <svg class="w-8 h-8 text-yellow-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-white mb-2 transition-colors duration-300">
                                Kelola Ruangan
                            </h3>
                            <p class="text-sm text-gray-600 group-hover:text-white/90 transition-colors duration-300">
                                Tambah dan edit data ruangan
                            </p>
                        </div>
                    </a>

                    <!-- User Management -->
                    <a href="{{ route('admin.users') }}" class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-indigo-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative p-6">
                            <div class="w-14 h-14 bg-indigo-100 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-4 transition-colors duration-300">
                                <svg class="w-8 h-8 text-indigo-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-white mb-2 transition-colors duration-300">
                                Kelola User
                            </h3>
                            <p class="text-sm text-gray-600 group-hover:text-white/90 transition-colors duration-300">
                                Atur hak akses pengguna
                            </p>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
