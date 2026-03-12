<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use App\Models\LocationUserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('id', '!=', auth()->id())->with('locations');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10);

        // Get assignment logs for the logs tab
        $logsQuery = LocationUserLog::with(['user', 'location', 'assignedBy']);
        $logs = $logsQuery->latest()->paginate(20, ['*'], 'logs_page');

        return view('admin.users.index', compact('users', 'logs'));
    }

    public function create()
    {
        $locations = Location::active()->orderBy('name')->get();
        return view('admin.users.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'user_type' => ['required', 'string', 'in:Admin,Member,Delivery Person'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'locations' => ['nullable', 'array'],
            'locations.*' => ['exists:locations,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // Attach locations if user is Delivery Person and log the assignments
        if ($request->user_type === 'Delivery Person' && $request->has('locations')) {
            $user->locations()->attach($request->locations);
            
            // Log each location assignment
            foreach ($request->locations as $locationId) {
                LocationUserLog::create([
                    'location_id' => $locationId,
                    'user_id' => $user->id,
                    'assigned_by' => auth()->id(),
                    'action' => 'assigned',
                    'notes' => 'Initial assignment during user creation',
                ]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $user->load(['locations', 'locationLogs']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $locations = Location::active()->orderBy('name')->get();
        $user->load('locations');
        return view('admin.users.edit', compact('user', 'locations'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'user_type' => ['required', 'string', 'in:Admin,Member,Delivery Person'],
            'locations' => ['nullable', 'array'],
            'locations.*' => ['exists:locations,id'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Password::min(8)],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        // Sync locations if user is Delivery Person and log changes
        if ($request->user_type === 'Delivery Person') {
            $currentLocations = $user->locations->pluck('id')->toArray();
            $newLocations = $request->locations ?? [];
            
            // Find added and removed locations
            $addedLocations = array_diff($newLocations, $currentLocations);
            $removedLocations = array_diff($currentLocations, $newLocations);
            
            // Log added locations
            foreach ($addedLocations as $locationId) {
                LocationUserLog::create([
                    'location_id' => $locationId,
                    'user_id' => $user->id,
                    'assigned_by' => auth()->id(),
                    'action' => 'assigned',
                    'notes' => 'Location assigned during user update',
                ]);
            }
            
            // Log removed locations
            foreach ($removedLocations as $locationId) {
                LocationUserLog::create([
                    'location_id' => $locationId,
                    'user_id' => $user->id,
                    'assigned_by' => auth()->id(),
                    'action' => 'unassigned',
                    'notes' => 'Location unassigned during user update',
                ]);
            }
            
            $user->locations()->sync($newLocations);
        } else {
            // Remove all locations if user is not Delivery Person and log
            $currentLocations = $user->locations->pluck('id')->toArray();
            foreach ($currentLocations as $locationId) {
                LocationUserLog::create([
                    'location_id' => $locationId,
                    'user_id' => $user->id,
                    'assigned_by' => auth()->id(),
                    'action' => 'unassigned',
                    'notes' => 'All locations removed - user type changed from Delivery Person',
                ]);
            }
            $user->locations()->detach();
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account from here.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }
}
