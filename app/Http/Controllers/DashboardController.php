<?php

namespace App\Http\Controllers;

use App\Models\MedicalCase;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard with status counters and overview.
     */
    public function index(Request $request)
    {
        $counts = [
            'total' => MedicalCase::count(),
            'planning' => MedicalCase::where('status', MedicalCase::STATUS_PLANNING)->count(),
            'in_review' => MedicalCase::where('status', MedicalCase::STATUS_IN_REVIEW)->count(),
            'approved' => MedicalCase::where('status', MedicalCase::STATUS_APPROVED)->count(),
            'completed' => MedicalCase::where('status', MedicalCase::STATUS_COMPLETED)->count(),
        ];

        // Recent cases for practical oversight
        $recentCases = MedicalCase::with('creator')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact('counts', 'recentCases'));
    }
}
