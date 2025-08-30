<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function getUsers()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return response()->json($users);
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => ['required', Rule::in(['student', 'organizer', 'admin'])],
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function deleteUser(User $user)
    {
        // Prevent deleting the current admin user
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Cannot delete your own account'], 400);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function getStats()
    {
        $stats = [
            'total_users' => User::count(),
            'total_events' => Event::count(),
            'total_registrations' => EventRegistration::count(),
            'pending_events' => Event::where('status', 'pending')->count(),
            'approved_events' => Event::where('status', 'approved')->count(),
            'students' => User::where('role', 'student')->count(),
            'organizers' => User::where('role', 'organizer')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        return response()->json($stats);
    }
}