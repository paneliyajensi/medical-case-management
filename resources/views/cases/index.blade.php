@extends('layouts.app')

@section('title', 'Case Registry')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.7rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em;">Medical Case Registry</h1>
        <p style="font-size: 0.95rem; color: var(--slate-500); margin-top: 0.25rem;">
            Tracking active surgical cases from initial draft to completion
        </p>
    </div>

    @can('create', App\Models\MedicalCase::class)
    <div>
        <a href="{{ route('cases.create') }}" class="btn btn-primary">
            + New Case
        </a>
    </div>
    @endcan
</div>

<!-- Search & Filter Controls -->
<div class="card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form method="GET" action="{{ route('cases.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <!-- Search -->
        <div style="flex: 2; min-width: 240px;">
            <label for="search" class="form-label" style="font-size: 0.8rem;">Search by Case Number or Surgeon</label>
            <input type="text" 
                   id="search" 
                   name="search" 
                   value="{{ request('search') }}" 
                   class="form-control" 
                   placeholder="e.g. CASE-1001 or Dr. Jenkins">
        </div>

        <!-- Filter by Status (Mandatory) -->
        <div style="flex: 1; min-width: 170px;">
            <label for="status" class="form-label" style="font-size: 0.8rem;">Status</label>
            <select id="status" name="status" class="form-control">
                <option value="">All Statuses</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ $s }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Bonus: Filter by Priority -->
        <div style="flex: 1; min-width: 150px;">
            <label for="priority" class="form-label" style="font-size: 0.8rem;">Priority</label>
            <select id="priority" name="priority" class="form-control">
                <option value="">All Priorities</option>
                @foreach ($priorities as $p)
                    <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>
                        {{ $p }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.1rem;">
                Filter
            </button>
            @if (request()->hasAny(['search', 'status', 'priority', 'sort']))
                <a href="{{ route('cases.index') }}" class="btn btn-secondary" style="padding: 0.6rem 1rem;">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Case Registry Table -->
<div class="card" style="box-shadow: var(--shadow-sm);">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>
                        <a href="{{ route('cases.index', array_merge(request()->query(), ['sort' => 'case_number', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none;">
                            Case # {{ request('sort') === 'case_number' ? (request('order') === 'asc' ? '▲' : '▼') : '' }}
                        </a>
                    </th>
                    <th>Patient Ref</th>
                    <th>
                        <a href="{{ route('cases.index', array_merge(request()->query(), ['sort' => 'surgeon_name', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none;">
                            Surgeon {{ request('sort') === 'surgeon_name' ? (request('order') === 'asc' ? '▲' : '▼') : '' }}
                        </a>
                    </th>
                    <th>Implant Type</th>
                    <th>
                        <a href="{{ route('cases.index', array_merge(request()->query(), ['sort' => 'status', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none;">
                            Status {{ request('sort') === 'status' ? (request('order') === 'asc' ? '▲' : '▼') : '' }}
                        </a>
                    </th>
                    <th>Priority</th>
                    <th>
                        <a href="{{ route('cases.index', array_merge(request()->query(), ['sort' => 'surgery_date', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none;">
                            Surgery Date {{ request('sort') === 'surgery_date' ? (request('order') === 'asc' ? '▲' : '▼') : '' }}
                        </a>
                    </th>
                    <th style="text-align: right; min-width: 170px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cases as $case)
                <tr>
                    <td>
                        <a href="{{ route('cases.show', $case) }}" style="font-weight: 700; color: var(--primary-dark); font-family: monospace; text-decoration: none;">
                            {{ $case->case_number }}
                        </a>
                    </td>
                    <td>
                        <span style="font-family: monospace; color: var(--slate-600); font-size: 0.875rem;">
                            {{ $case->patient_reference }}
                        </span>
                    </td>
                    <td>{{ $case->surgeon_name }}</td>
                    <td>{{ $case->implant_type }}</td>
                    <td>
                        @php
                            $statusSlug = strtolower(str_replace(' ', '-', $case->status));
                        @endphp
                        <span class="status-badge status-{{ $statusSlug }}">
                            <span class="status-dot"></span>
                            {{ $case->status }}
                        </span>
                    </td>
                    <td>
                        <span class="priority-badge priority-{{ strtolower($case->priority) }}">
                            {{ $case->priority }}
                        </span>
                    </td>
                    <td>
                        {{ $case->surgery_date ? $case->surgery_date->format('M d, Y') : '—' }}
                    </td>
                    <!-- View / Edit / Delete buttons shown only to roles allowed to use them -->
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 0.35rem; align-items: center;">
                            <!-- View button (available to all roles) -->
                            <a href="{{ route('cases.show', $case) }}" class="btn btn-secondary btn-sm" title="View Details">
                                View
                            </a>

                            <!-- Edit button (Admin only) -->
                            @can('update', $case)
                            <a href="{{ route('cases.edit', $case) }}" class="btn btn-secondary btn-sm" title="Edit Case">
                                Edit
                            </a>
                            @endcan

                            <!-- Delete button (Admin only) -->
                            @can('delete', $case)
                            <form action="{{ route('cases.destroy', $case) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete case {{ $case->case_number }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Case">
                                    Delete
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 2.5rem; color: var(--slate-500);">
                        No cases matched your filter criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination (10 per page) -->
    <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--slate-200); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="font-size: 0.85rem; color: var(--slate-600);">
            Showing {{ $cases->firstItem() ?? 0 }} to {{ $cases->lastItem() ?? 0 }} of {{ $cases->total() }} cases
        </div>
        <div>
            {{ $cases->links('pagination.custom') }}
        </div>
    </div>
</div>
@endsection
