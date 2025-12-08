<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()?->isAdmin()) {
                return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $totalBookings = Booking::count();
        $todayBookings = Booking::where('booking_date', today())->count();
        $upcomingBookings = Booking::where('booking_date', '>', today())
            ->where('status', 'approved')
            ->count();
        $totalUsers = User::where('is_admin', false)->count();

        $recentBookings = Booking::with(['user', 'room'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $todaysSchedule = Booking::with(['user', 'room'])
            ->where('booking_date', today())
            ->where('status', 'approved')
            ->orderBy('start_time')
            ->get();

        return view('admin.dashboard', compact(
            'totalRooms',
            'availableRooms',
            'totalBookings',
            'todayBookings',
            'upcomingBookings',
            'totalUsers',
            'recentBookings',
            'todaysSchedule'
        ));
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'room']);

        // Filter by date
        if ($request->has('date')) {
            $query->where('booking_date', $request->date);
        }

        // Filter by room
        if ($request->has('room_id') && $request->room_id != '') {
            $query->where('room_id', $request->room_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        $rooms = Room::all();

        return view('admin.bookings', compact('bookings', 'rooms'));
    }

    public function cancelBooking(Booking $booking)
    {
        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'Peminjaman ini tidak dapat dibatalkan.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_by' => auth()->user()->name . ' (Admin)',
            'cancellation_reason' => request('reason', 'Dibatalkan oleh Admin')
        ]);

        return back()->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    public function users()
    {
        $users = User::withCount('bookings')
            ->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        // Don't allow admin to remove their own admin status
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status admin Anda sendiri.');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $message = $user->is_admin
            ? 'User berhasil dijadikan admin.'
            : 'Status admin user berhasil dicabut.';

        return back()->with('success', $message);
    }

    public function reports(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        $selectedRooms = $request->get('room_ids', []);

        // Get all rooms for filter
        $rooms = Room::orderBy('name')->get();

        // If no rooms selected, select all by default
        if (empty($selectedRooms)) {
            $selectedRooms = $rooms->pluck('id')->toArray();
        }

        // Get bookings grouped by date
        $bookingsQuery = Booking::with(['user', 'room'])
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->whereIn('room_id', $selectedRooms)
            ->where('status', 'approved')
            ->orderBy('booking_date')
            ->orderBy('room_id')
            ->orderBy('start_time');

        $bookings = $bookingsQuery->get();

        // Group bookings by date
        $bookingsByDate = $bookings->groupBy(function ($booking) {
            return $booking->booking_date->format('Y-m-d');
        });

        return view('admin.reports', compact(
            'rooms',
            'selectedRooms',
            'bookingsByDate',
            'startDate',
            'endDate'
        ));
    }

    public function downloadReportPDF(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        $selectedRooms = $request->get('room_ids', []);

        // Get all rooms for filter
        $rooms = Room::orderBy('name')->get();

        // If no rooms selected, select all by default
        if (empty($selectedRooms)) {
            $selectedRooms = $rooms->pluck('id')->toArray();
        }

        // Get bookings grouped by date
        $bookingsQuery = Booking::with(['user', 'room'])
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->whereIn('room_id', $selectedRooms)
            ->where('status', 'approved')
            ->orderBy('booking_date')
            ->orderBy('room_id')
            ->orderBy('start_time');

        $bookings = $bookingsQuery->get();

        // Group bookings by date
        $bookingsByDate = $bookings->groupBy(function ($booking) {
            return $booking->booking_date->format('Y-m-d');
        });

        $pdf = \PDF::loadView('admin.reports-pdf', compact(
            'bookingsByDate',
            'startDate',
            'endDate',
            'selectedRooms',
            'rooms'
        ));

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Set options for better rendering
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans'
        ]);

        $filename = 'Laporan-Peminjaman-' . Carbon::parse($startDate)->format('d-M-Y') . '-sd-' . Carbon::parse($endDate)->format('d-M-Y') . '.pdf';

        return $pdf->download($filename);
    }
}
