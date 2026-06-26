<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserActivity;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = UserActivity::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('activity')) {
            $activity = UserActivity::normalizeActivity($request->activity) ?? $request->activity;
            $query->whereIn('activity', UserActivity::activityFilterValues($activity));
        }
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get();
        $activityTypes = UserActivity::distinctActivityTypes();
        $modules = UserActivity::distinct()->orderBy('module')->pluck('module');
        $totalLogs = UserActivity::count();

        return view('admin.activities.index', compact('activities', 'users', 'activityTypes', 'modules', 'totalLogs'));
    }

    public function show($id)
    {
        $activity = UserActivity::with('user')->findOrFail($id);
        return view('admin.activities.show', compact('activity'));
    }
}
