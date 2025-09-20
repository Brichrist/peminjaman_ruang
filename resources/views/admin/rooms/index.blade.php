<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Daftar Ruangan</h3>
                        <a href="{{ route('rooms.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Tambah Ruangan
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">Nama Ruangan</th>
                                    <th class="px-4 py-2 text-left">Deskripsi</th>
                                    <th class="px-4 py-2 text-left">Kapasitas</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Total Peminjaman</th>
                                    <th class="px-4 py-2 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rooms as $room)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $room->name }}</td>
                                        <td class="px-4 py-2">{{ Str::limit($room->description, 50) }}</td>
                                        <td class="px-4 py-2">{{ $room->capacity }} orang</td>
                                        <td class="px-4 py-2">
                                            @if($room->status == 'available')
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Tersedia</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Tidak Tersedia</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">{{ $room->bookings_count }} peminjaman</td>
                                        <td class="px-4 py-2">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('rooms.show', $room) }}" class="text-blue-600 hover:underline">Lihat</a>
                                                <a href="{{ route('rooms.edit', $room) }}" class="text-green-600 hover:underline">Edit</a>
                                                <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">
                                            Belum ada ruangan. <a href="{{ route('rooms.create') }}" class="text-blue-600 hover:underline">Tambah ruangan baru</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $rooms->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>