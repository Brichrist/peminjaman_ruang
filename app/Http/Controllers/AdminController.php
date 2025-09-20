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
            if (!auth()->check() || !auth()->user()->isAdmin()) {
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

        $bookingStats = Booking::whereBetween('booking_date', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed
            ')
            ->first();

        $roomUsage = Room::withCount(['bookings' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('booking_date', [$startDate, $endDate])
                  ->where('status', 'approved');
        }])
        ->orderBy('bookings_count', 'desc')
        ->get();

        $topUsers = User::withCount(['bookings' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('booking_date', [$startDate, $endDate])
                  ->where('status', 'approved');
        }])
        ->where('is_admin', false)
        ->orderBy('bookings_count', 'desc')
        ->limit(10)
        ->get();

        return view('admin.reports', compact(
            'bookingStats',
            'roomUsage',
            'topUsers',
            'startDate',
            'endDate'
        ));
    }
}