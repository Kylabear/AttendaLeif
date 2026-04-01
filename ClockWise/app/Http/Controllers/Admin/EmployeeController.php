<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display the employee list for admins.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'in:all,admin,employee'],
        ]);

        $search = trim((string) ($filters['q'] ?? ''));
        $role = $filters['role'] ?? 'all';

        $employees = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('id_number', 'like', "%{$search}%");
                });
            })
            ->when($role !== 'all', function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.employees.index', [
            'employees' => $employees,
            'search' => $search,
            'selectedRole' => $role,
        ]);
    }

    /**
     * Update a user's role.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,employee'],
        ]);

        if ($request->user()->id === $user->id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        if ($user->role === 'admin' && $validated['role'] !== 'admin') {
            $adminCount = User::query()->where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'You cannot remove the last admin.');
            }
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('status', 'Role updated successfully.');
    }

    /**
     * Delete a user account from the admin panel.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->id === $user->id) {
            return back()->with('error', 'You cannot delete your own account here.');
        }

        if ($user->role === 'admin') {
            $adminCount = User::query()->where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'You cannot delete the last admin.');
            }
        }

        $user->delete();

        return back()->with('status', 'User removed successfully.');
    }
}
