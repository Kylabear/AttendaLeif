<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function daily(Request $request): View
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date'],
        ]);

        $date = $validated['date'] ?? Carbon::today()->toDateString();

        $employees = User::query()
            ->where('role', User::ROLE_EMPLOYEE)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'id_number']);

        $scheduleByUser = WorkSchedule::query()
            ->whereDate('schedule_date', $date)
            ->whereIn('user_id', $employees->pluck('id'))
            ->get()
            ->keyBy('user_id');

        $attendanceByUser = Attendance::query()
            ->whereDate('attendance_date', $date)
            ->whereIn('user_id', $employees->pluck('id'))
            ->get()
            ->keyBy('user_id');

        $summary = [
            'present' => 0,
            'absent' => 0,
            'on_leave' => 0,
            'day_off' => 0,
            'total' => $employees->count(),
        ];

        $rows = $employees->map(function ($employee) use ($scheduleByUser, $attendanceByUser, &$summary) {
            $schedule = $scheduleByUser->get($employee->id);
            $attendance = $attendanceByUser->get($employee->id);

            $status = $schedule?->status ?? 'workday';
            if ($status === 'on_leave') {
                $computedStatus = 'on_leave';
            } elseif ($status === 'day_off') {
                $computedStatus = 'day_off';
            } elseif ($attendance && $attendance->clock_in_at) {
                $computedStatus = 'present';
            } else {
                $computedStatus = 'absent';
            }

            $summary[$computedStatus]++;

            return [
                'employee' => $employee,
                'status' => $computedStatus,
                'schedule_note' => $schedule?->note,
                'clock_in_at' => $attendance?->clock_in_at,
                'clock_out_at' => $attendance?->clock_out_at,
            ];
        });

        return view('admin.attendance.daily', [
            'date' => $date,
            'rows' => $rows,
            'summary' => $summary,
        ]);
    }

    public function trends(Request $request): View
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $to = Carbon::parse($validated['to'] ?? Carbon::today()->toDateString())->startOfDay();
        $from = Carbon::parse($validated['from'] ?? $to->copy()->subDays(13)->toDateString())->startOfDay();

        if ($from->gt($to)) {
            [$from, $to] = [$to, $from];
        }

        $employeeIds = User::query()
            ->where('role', User::ROLE_EMPLOYEE)
            ->pluck('id');

        $schedules = WorkSchedule::query()
            ->whereIn('user_id', $employeeIds)
            ->whereBetween('schedule_date', [$from->toDateString(), $to->toDateString()])
            ->get()
            ->groupBy(fn ($item) => $item->schedule_date->toDateString())
            ->map(fn ($items) => $items->keyBy('user_id'));

        $attendances = Attendance::query()
            ->whereIn('user_id', $employeeIds)
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->whereNotNull('clock_in_at')
            ->get()
            ->groupBy(fn ($item) => $item->attendance_date->toDateString())
            ->map(fn ($items) => $items->keyBy('user_id'));

        $trendRows = collect();

        foreach (CarbonPeriod::create($from, $to) as $day) {
            $date = $day->toDateString();
            $daySchedules = $schedules->get($date, collect());
            $dayAttendance = $attendances->get($date, collect());

            $counts = [
                'present' => 0,
                'absent' => 0,
                'on_leave' => 0,
                'day_off' => 0,
                'total' => $employeeIds->count(),
            ];

            foreach ($employeeIds as $userId) {
                $status = $daySchedules->get($userId)?->status ?? 'workday';

                if ($status === 'on_leave') {
                    $counts['on_leave']++;
                    continue;
                }

                if ($status === 'day_off') {
                    $counts['day_off']++;
                    continue;
                }

                if ($dayAttendance->has($userId)) {
                    $counts['present']++;
                } else {
                    $counts['absent']++;
                }
            }

            $trendRows->push([
                'date' => $date,
                'counts' => $counts,
            ]);
        }

        return view('admin.attendance.trends', [
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'trendRows' => $trendRows,
        ]);
    }

    public function exportDaily(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date'],
        ]);

        $date = $validated['date'] ?? Carbon::today()->toDateString();

        $employees = User::query()
            ->where('role', User::ROLE_EMPLOYEE)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'id_number']);

        $scheduleByUser = WorkSchedule::query()
            ->whereDate('schedule_date', $date)
            ->whereIn('user_id', $employees->pluck('id'))
            ->get()
            ->keyBy('user_id');

        $attendanceByUser = Attendance::query()
            ->whereDate('attendance_date', $date)
            ->whereIn('user_id', $employees->pluck('id'))
            ->get()
            ->keyBy('user_id');

        $filename = 'daily_attendance_' . $date . '.csv';

        return response()->streamDownload(function () use ($employees, $scheduleByUser, $attendanceByUser) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Employee', 'ID Number', 'Email', 'Status', 'Clock In', 'Clock Out', 'Note']);

            foreach ($employees as $employee) {
                $schedule = $scheduleByUser->get($employee->id);
                $attendance = $attendanceByUser->get($employee->id);

                $status = $schedule?->status ?? 'workday';
                if ($status === 'on_leave') {
                    $computedStatus = 'on_leave';
                } elseif ($status === 'day_off') {
                    $computedStatus = 'day_off';
                } elseif ($attendance && $attendance->clock_in_at) {
                    $computedStatus = 'present';
                } else {
                    $computedStatus = 'absent';
                }

                fputcsv($handle, [
                    $attendance?->attendance_date?->toDateString() ?? $schedule?->schedule_date?->toDateString() ?? now()->toDateString(),
                    $employee->name,
                    $employee->id_number,
                    $employee->email,
                    str_replace('_', ' ', $computedStatus),
                    optional($attendance?->clock_in_at)->format('Y-m-d H:i:s') ?? '',
                    optional($attendance?->clock_out_at)->format('Y-m-d H:i:s') ?? '',
                    $schedule?->note ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportTrends(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $to = Carbon::parse($validated['to'] ?? Carbon::today()->toDateString())->startOfDay();
        $from = Carbon::parse($validated['from'] ?? $to->copy()->subDays(13)->toDateString())->startOfDay();

        if ($from->gt($to)) {
            [$from, $to] = [$to, $from];
        }

        $employeeIds = User::query()
            ->where('role', User::ROLE_EMPLOYEE)
            ->pluck('id');

        $schedules = WorkSchedule::query()
            ->whereIn('user_id', $employeeIds)
            ->whereBetween('schedule_date', [$from->toDateString(), $to->toDateString()])
            ->get()
            ->groupBy(fn ($item) => $item->schedule_date->toDateString())
            ->map(fn ($items) => $items->keyBy('user_id'));

        $attendances = Attendance::query()
            ->whereIn('user_id', $employeeIds)
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->whereNotNull('clock_in_at')
            ->get()
            ->groupBy(fn ($item) => $item->attendance_date->toDateString())
            ->map(fn ($items) => $items->keyBy('user_id'));

        $filename = 'attendance_trends_' . $from->toDateString() . '_to_' . $to->toDateString() . '.csv';

        return response()->streamDownload(function () use ($from, $to, $employeeIds, $schedules, $attendances) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Present', 'Absent', 'On Leave', 'Day Off', 'Total']);

            foreach (CarbonPeriod::create($from, $to) as $day) {
                $date = $day->toDateString();
                $daySchedules = $schedules->get($date, collect());
                $dayAttendance = $attendances->get($date, collect());

                $counts = [
                    'present' => 0,
                    'absent' => 0,
                    'on_leave' => 0,
                    'day_off' => 0,
                    'total' => $employeeIds->count(),
                ];

                foreach ($employeeIds as $userId) {
                    $status = $daySchedules->get($userId)?->status ?? 'workday';

                    if ($status === 'on_leave') {
                        $counts['on_leave']++;
                        continue;
                    }

                    if ($status === 'day_off') {
                        $counts['day_off']++;
                        continue;
                    }

                    if ($dayAttendance->has($userId)) {
                        $counts['present']++;
                    } else {
                        $counts['absent']++;
                    }
                }

                fputcsv($handle, [
                    $date,
                    $counts['present'],
                    $counts['absent'],
                    $counts['on_leave'],
                    $counts['day_off'],
                    $counts['total'],
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
