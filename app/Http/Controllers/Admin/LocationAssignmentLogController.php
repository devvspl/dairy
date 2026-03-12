<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LocationUserLog;
use Illuminate\Http\Request;

class LocationAssignmentLogController extends Controller
{
    public function index(Request $request)
    {
        $query = LocationUserLog::with(['user', 'location', 'assignedBy']);

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by location
        if ($request->has('location_id') && $request->location_id) {
            $query->where('location_id', $request->location_id);
        }

        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->paginate(20);

        return view('admin.location-assignment-logs.index', compact('logs'));
    }
}
