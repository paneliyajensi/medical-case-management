<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCaseRequest;
use App\Http\Requests\UpdateCaseRequest;
use App\Models\MedicalCase;
use Illuminate\Http\Request;

class CaseApiController extends Controller
{
    /**
     * List paginated cases.
     * GET /api/cases
     * Roles: Admin, Viewer
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', MedicalCase::class);

        $query = MedicalCase::with('creator');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('surgeon_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $cases = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $cases,
            'message' => 'Cases retrieved successfully.',
        ], 200);
    }

    /**
     * Create a new case.
     * POST /api/cases
     * Roles: Admin only
     */
    public function store(StoreCaseRequest $request)
    {
        $this->authorize('create', MedicalCase::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $case = MedicalCase::create($data);
        $case->load('creator');

        return response()->json([
            'success' => true,
            'data' => $case,
            'message' => 'Case created successfully.',
        ], 201);
    }

    /**
     * Retrieve a specific case by ID.
     * GET /api/cases/{id}
     * Roles: Admin, Viewer
     */
    public function show($id)
    {
        $case = MedicalCase::with('creator')->find($id);

        if (!$case) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Case not found.',
            ], 404);
        }

        $this->authorize('view', $case);

        return response()->json([
            'success' => true,
            'data' => $case,
            'message' => 'Case retrieved successfully.',
        ], 200);
    }

    /**
     * Update an existing case.
     * PUT /api/cases/{id}
     * Roles: Admin only
     */
    public function update(UpdateCaseRequest $request, $id)
    {
        $case = MedicalCase::find($id);

        if (!$case) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Case not found.',
            ], 404);
        }

        $this->authorize('update', $case);

        $case->update($request->validated());
        $case->load('creator');

        return response()->json([
            'success' => true,
            'data' => $case,
            'message' => 'Case updated successfully.',
        ], 200);
    }

    /**
     * Delete an existing case.
     * DELETE /api/cases/{id}
     * Roles: Admin only
     */
    public function destroy($id)
    {
        $case = MedicalCase::find($id);

        if (!$case) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Case not found.',
            ], 404);
        }

        $this->authorize('delete', $case);

        $caseNumber = $case->case_number;
        $case->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => "Case {$caseNumber} deleted successfully.",
        ], 200);
    }
}
