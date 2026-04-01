<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = request()->user();

        if ($user->isAdmin()) {
            $date = Carbon::today()->toDateString();
            $summary = $this->buildDailySummary($date);
            $openShifts = Attendance::query()
                ->whereDate('attendance_date', $date)
                ->whereNotNull('clock_in_at')
                ->whereNull('clock_out_at')
                ->count();

            return view('dashboard.admin', [
                'date' => $date,
                'summary' => $summary,
                'openShifts' => $openShifts,
            ]);
        }

        $date = Carbon::today()->toDateString();
        $attendance = Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('attendance_date', $date)
            ->first();

        $schedule = WorkSchedule::query()
            ->where('user_id', $user->id)
            ->whereDate('schedule_date', $date)
            ->first();

        $todayStatus = $schedule?->status ?? 'workday';

        $recentAttendances = Attendance::query()
            ->where('user_id', $user->id)
            ->latest('attendance_date')
            ->limit(10)
            ->get();

        return view('dashboard.employee', [
            'attendance' => $attendance,
            'todayStatus' => $todayStatus,
            'recentAttendances' => $recentAttendances,
        ]);
    }

    private function buildDailySummary(string $date): array
    {
        $employeeIds = User::query()
            ->where('role', User::ROLE_EMPLOYEE)
            ->pluck('id');

        $schedules = WorkSchedule::query()
            ->whereDate('schedule_date', $date)
            ->whereIn('user_id', $employeeIds)
            ->get()
            ->keyBy('user_id');

        $attendances = Attendance::query()
            ->whereDate('attendance_date', $date)
            ->whereIn('user_id', $employeeIds)
            ->whereNotNull('clock_in_at')
            ->get()
            ->keyBy('user_id');

        $summary = [
            'present' => 0,
            'absent' => 0,
            'on_leave' => 0,
            'day_off' => 0,
            'total' => $employeeIds->count(),
        ];

        foreach ($employeeIds as $userId) {
            $schedule = $schedules->get($userId);
            $status = $schedule?->status ?? 'workday';

            if ($status === 'on_leave') {
                $summary['on_leave']++;
                continue;
            }

            if ($status === 'day_off') {
                $summary['day_off']++;
                continue;
            }

            if ($attendances->has($userId)) {
                $summary['present']++;
            } else {
                $summary['absent']++;
            }
        }

        return $summary;
    }
}
