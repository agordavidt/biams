<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    //
    public function index()
    {
        return view('admin.index');
    }

    // Show all pending applications
    public function applicationIndex()
    {
        $applications = User::where('status', 'pending')->get();
        return view('admin.applications', compact('applications'));
    }

    // Approve an application
    public function approve(User $user)
    {
        $user->update(['status' => 'approved']);
        // Send approval notification (email or dashboard)
        return redirect()->route('admin.applications')->with('success', 'Application approved successfully.');
    }

    // Reject an application
    public function reject(User $user)
    {
        $user->update(['status' => 'rejected']);
        // Send rejection notification (email or dashboard)
        return redirect()->route('admin.applications')->with('success', 'Application rejected successfully.');
    }


}




