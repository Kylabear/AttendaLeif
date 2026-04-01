<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function clockIn(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $schedule = WorkSchedule::query()
            ->where('user_id', $user->id)
            ->whereDate('schedule_date', $today)
            ->first();

        $scheduleStatus = $schedule?->status ?? 'workday';
        if (in_array($scheduleStatus, ['day_off', 'on_leave'], true)) {
            return back()->with('error', 'You are tagged as ' . str_replace('_', ' ', $scheduleStatus) . ' today.');
        }

        $attendance = Attendance::query()->firstOrCreate(
            ['user_id' => $user->id, 'attendance_date' => $today],
            ['clock_in_at' => now()]
        );

        if ($attendance->clock_in_at !== null && ! $attendance->wasRecentlyCreated) {
            return back()->with('error', 'You already clocked in today.');
        }

        if ($attendance->clock_in_at === null) {
            $attendance->update(['clock_in_at' => now()]);
        }

        return back()->with('status', 'Clock-in recorded at ' . now()->format('h:i A') . '.');
    }

    public function clockOut(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if (! $attendance || $attendance->clock_in_at === null) {
            return back()->with('error', 'You need to clock in first.');
        }

        if ($attendance->clock_out_at !== null) {
            return back()->with('error', 'You already clocked out today.');
        }

        $attendance->update(['clock_out_at' => now()]);

        return back()->with('status', 'Clock-out recorded at ' . now()->format('h:i A') . '.');
    }
    /**
     * Allow employee to set their own day off or on leave status for today.
     */
    public function setStatus(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();
        $status = $request->input('status');

        if (!in_array($status, ['workday', 'day_off', 'on_leave'], true)) {
            return back()->with('error', 'Invalid status.');
        }

        $schedule = WorkSchedule::query()
            ->firstOrNew([
                'user_id' => $user->id,
                'schedule_date' => $today,
            ]);
        $schedule->status = $status;
        $schedule->save();

        $msg = $status === 'workday'
            ? 'Status reset to workday. You can now clock in/out.'
            : 'Your status for today is set to ' . str_replace('_', ' ', $status) . '. No clock-in/out required.';

        return back()->with('status', $msg);
    }
}
