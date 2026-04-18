<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalItineraries = Itinerary::count();
        $publicItineraries = Itinerary::where('is_public', true)->count();
        $recentItineraries = Itinerary::with('user')
            ->withCount('destinations')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('totalUsers', 'totalItineraries', 'publicItineraries', 'recentItineraries'));
    }

    public function users()
    {
        $users = User::withCount('itineraries')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:admin,user']);
        Role::findOrCreate((string) $request->string('role'), 'web');
        $user->syncRoles($request->role);
        return back()->with('success', 'Role updated.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    // Admin Reporting
    public function generateReport()
    {
        $data = [
            'totalUsers' => User::count(),
            'totalItineraries' => Itinerary::count(),
            'activeItineraries' => Itinerary::where('is_public', true)->count(),
            'topUsers' => User::withCount('itineraries')->orderBy('itineraries_count', 'desc')->limit(5)->get(),
        ];

        $pdf = Pdf::loadView('pdf.admin-report', $data);
        return $pdf->download('admin-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
