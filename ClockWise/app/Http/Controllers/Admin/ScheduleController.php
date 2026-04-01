<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $from = $filters['from'] ?? now()->startOfMonth()->toDateString();
        $to = $filters['to'] ?? now()->endOfMonth()->toDateString();

        $schedules = WorkSchedule::query()
            ->with('user:id,name,id_number')
            ->when(isset($filters['user_id']), function ($query) use ($filters) {
                $query->where('user_id', $filters['user_id']);
            })
            ->whereBetween('schedule_date', [$from, $to])
            ->orderBy('schedule_date')
            ->paginate(20)
            ->withQueryString();

        $employees = User::query()
            ->where('role', User::ROLE_EMPLOYEE)
            ->orderBy('name')
            ->get(['id', 'name', 'id_number']);

        return view('admin.schedules.index', [
            'schedules' => $schedules,
            'employees' => $employees,
            'from' => $from,
            'to' => $to,
            'selectedUserId' => $filters['user_id'] ?? null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'schedule_date' => ['required', 'date'],
            'status' => ['required', 'in:workday,day_off,on_leave'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        WorkSchedule::query()->updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'schedule_date' => $validated['schedule_date'],
            ],
            [
                'status' => $validated['status'],
                'note' => $validated['note'] ?? null,
            ]
        );

        return back()->with('status', 'Schedule saved successfully.');
    }

    public function destroy(WorkSchedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return back()->with('status', 'Schedule entry deleted.');
    }
}
