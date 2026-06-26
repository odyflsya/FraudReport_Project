<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountApproved;
use App\Models\User;
use App\Models\UserActivity;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserManagementController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'pending' => User::where('status', 'pending')->count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::whereIn('status', ['inactive', 'rejected'])->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        try {
            if ($user->hasVerifiedEmail()) {
                Mail::to($user->email)->send(new AccountApproved($user));
            } else {
                $this->otpService->reissue($user->email);
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim email persetujuan akun', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }

        UserActivity::create([
            'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role,
            'activity' => 'Approve User',
            'module' => 'User Management',
            'description' => 'Approved user: '.$user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $message = $user->hasVerifiedEmail()
            ? 'User berhasil disetujui. User dapat login dengan email dan password.'
            : 'User berhasil disetujui. Karena email belum terverifikasi, OTP baru telah dikirim agar user bisa menyelesaikan verifikasi.';

        return back()->with('success', $message);
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'rejected']);

        UserActivity::create([
            'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role,
            'activity' => 'Reject User',
            'module' => 'User Management',
            'description' => 'Rejected user: '.$user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'User berhasil ditolak.');
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        UserActivity::create([
            'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role,
            'activity' => 'Activate User',
            'module' => 'User Management',
            'description' => 'Activated user: '.$user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'User berhasil diaktifkan.');
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['status' => 'inactive']);

        UserActivity::create([
            'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role,
            'activity' => 'Deactivate User',
            'module' => 'User Management',
            'description' => 'Deactivated user: '.$user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'User berhasil dinonaktifkan.');
    }

    public function changeRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:admin,user']);
        $user = User::findOrFail($id);
        $old = $user->role;
        $user->update(['role' => $request->role]);

        UserActivity::create([
            'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role,
            'activity' => 'Change Role',
            'module' => 'User Management',
            'description' => "Changed role for {$user->email} from {$old} to {$request->role}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Role updated');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $email = $user->email;
        $user->delete();

        UserActivity::create([
            'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role,
            'activity' => 'Delete User',
            'module' => 'User Management',
            'description' => 'Deleted user: '.$email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'User deleted');
    }
}
