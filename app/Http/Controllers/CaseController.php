<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCaseRequest;
use App\Http\Requests\UpdateCaseRequest;
use App\Models\MedicalCase;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    /**
     * Display a listing of cases with search, status filtering, and 10 items per page pagination.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', MedicalCase::class);

        $query = MedicalCase::with('creator');

        // Search by Case Number or Surgeon Name
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('surgeon_name', 'like', "%{$search}%");
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Bonus: Filter by Priority if provided
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Sorting (default newest first)
        $allowedSorts = ['case_number', 'patient_reference', 'surgeon_name', 'status', 'priority', 'surgery_date', 'created_at'];
        $sortBy = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'created_at';
        $sortOrder = $request->get('order') === 'asc' ? 'asc' : 'desc';

        $cases = $query->orderBy($sortBy, $sortOrder)
            ->paginate(10)
            ->withQueryString();

        $statuses = MedicalCase::STATUSES;
        $priorities = MedicalCase::PRIORITIES;

        return view('cases.index', compact('cases', 'statuses', 'priorities', 'sortBy', 'sortOrder'));
    }

    /**
     * Show the form for creating a new case.
     */
    public function create()
    {
        $this->authorize('create', MedicalCase::class);

        $case = new MedicalCase();
        $statuses = MedicalCase::STATUSES;
        $priorities = MedicalCase::PRIORITIES;

        return view('cases.create', compact('case', 'statuses', 'priorities'));
    }

    /**
     * Store a newly created case in database.
     */
    public function store(StoreCaseRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $case = MedicalCase::create($data);

        return redirect()->route('cases.show', $case)
            ->with('success', "Case {$case->case_number} was created successfully.");
    }

    /**
     * Display the specified case details.
     */
    public function show(MedicalCase $case)
    {
        $this->authorize('view', $case);

        $case->load('creator');

        return view('cases.show', compact('case'));
    }

    /**
     * Show the form for editing the specified case.
     */
    public function edit(MedicalCase $case)
    {
        $this->authorize('update', $case);

        $statuses = MedicalCase::STATUSES;
        $priorities = MedicalCase::PRIORITIES;

        return view('cases.edit', compact('case', 'statuses', 'priorities'));
    }

    /**
     * Update the specified case in database.
     */
    public function update(UpdateCaseRequest $request, MedicalCase $case)
    {
        $this->authorize('update', $case);

        $case->update($request->validated());

        return redirect()->route('cases.show', $case)
            ->with('success', "Case {$case->case_number} was updated successfully.");
    }

    /**
     * Remove the specified case from database (Admin only).
     */
    public function destroy(MedicalCase $case)
    {
        $this->authorize('delete', $case);

        $caseNumber = $case->case_number;
        $case->delete();

        return redirect()->route('cases.index')
            ->with('success', "Case {$caseNumber} has been permanently deleted.");
    }
}
