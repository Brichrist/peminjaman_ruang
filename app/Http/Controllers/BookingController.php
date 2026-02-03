<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create()
    {
        $rooms = Room::where('status', 'available')->get();
        $timeSlots = Booking::getTimeSlots();

        return view('bookings.create', compact('rooms', 'timeSlots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'activity' => 'required|string|max:500',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => ['required', 'string', 'regex:/^(0|62)[0-9]{9,13}$/']
        ]);

        // Check if the time slot is available
        if (!Booking::isTimeSlotAvailable(
            $validated['room_id'],
            $validated['booking_date'],
            $validated['start_time'],
            $validated['end_time']
        )) {
            return back()->withErrors(['time' => 'Slot waktu yang dipilih tidak tersedia.'])->withInput();
        }

        // Create booking (auto-approved) with guest info
        $booking = Booking::create([
            'room_id' => $validated['room_id'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'activity' => $validated['activity'],
            'guest_name' => $validated['guest_name'],
            'guest_phone' => $validated['guest_phone'],
            'status' => 'approved'
        ]);

        return redirect()->route('bookings.room-schedule')
            ->with('success', 'Peminjaman ruangan berhasil dibuat dan langsung disetujui.');
    }

    public function schedule(Request $request)
    {
        $rooms = Room::where('status', 'available')->get();
        $selectedRoom = null;
        $selectedDate = $request->get('date', now()->format('Y-m-d'));
        $bookings = collect();

        if ($request->has('room_id') && $request->room_id != '') {
            $selectedRoom = Room::find($request->room_id);
            $bookings = Booking::with('user')
                ->where('room_id', $request->room_id)
                ->where('booking_date', $selectedDate)
                ->where('status', 'approved')
                ->orderBy('start_time')
                ->get();
        }

        $timeSlots = Booking::getTimeSlots();

        return view('bookings.schedule', compact('rooms', 'selectedRoom', 'selectedDate', 'bookings', 'timeSlots'));
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date|after_or_equal:today'
        ]);

        $bookings = Booking::where('room_id', $request->room_id)
            ->where('booking_date', $request->date)
            ->where('status', 'approved')
            ->select('id', 'user_id', 'guest_name', 'guest_phone', 'start_time', 'end_time', 'activity')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'activity' => $booking->activity,
                    'contact_name' => $booking->getContactName(),
                    'contact_phone' => $booking->getFormattedWhatsApp()
                ];
            });

        return response()->json(['bookings' => $bookings]);
    }

    public function roomSchedule()
    {
        $rooms = Room::where('status', 'available')->orderBy('name')->get();

        return view('bookings.room-schedule', compact('rooms'));
    }
}