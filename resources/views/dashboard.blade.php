@extends('layouts.app')

@section('title', 'Clinical Dashboard')

@section('content')
<div style="margin-bottom: 2rem; display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em;">Implant Case Overview</h1>
        <p style="font-size: 0.95rem; color: var(--slate-500); margin-top: 0.25rem;">
            Real-time status tracking across surgical implant procedures &bull; Signed in as <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->role }})
        </p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('cases.index') }}" class="btn btn-secondary">
            View Case Registry
        </a>
        @can('create', App\Models\MedicalCase::class)
        <a href="{{ route('cases.create') }}" class="btn btn-primary">
            + New Case
        </a>
        @endcan
    </div>
</div>

<!-- 5 Mandatory Counts: Total, Planning, In Review, Approved, Completed -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.15rem; margin-bottom: 2.25rem;">
    <!-- Total Cases -->
    <div class="card" style="padding: 1.25rem; border-left: 4px solid var(--slate-700);">
        <div style="font-size: 0.78rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em;">Total Cases</div>
        <div style="font-size: 2.15rem; font-weight: 800; color: var(--slate-900); margin: 0.35rem 0 0.2rem;">{{ $counts['total'] }}</div>
        <div style="font-size: 0.8rem; color: var(--slate-500);">All tracked implant files</div>
    </div>

    <!-- Planning -->
    <div class="card" style="padding: 1.25rem; border-left: 4px solid var(--warning);">
        <div style="font-size: 0.78rem; font-weight: 700; color: #b45309; text-transform: uppercase; letter-spacing: 0.05em;">Planning</div>
        <div style="font-size: 2.15rem; font-weight: 800; color: var(--slate-900); margin: 0.35rem 0 0.2rem;">{{ $counts['planning'] }}</div>
        <div style="font-size: 0.8rem; color: var(--slate-500);">Pre-op CAD & sizing</div>
    </div>

    <!-- In Review -->
    <div class="card" style="padding: 1.25rem; border-left: 4px solid var(--info);">
        <div style="font-size: 0.78rem; font-weight: 700; color: #1d4ed8; text-transform: uppercase; letter-spacing: 0.05em;">In Review</div>
        <div style="font-size: 2.15rem; font-weight: 800; color: var(--slate-900); margin: 0.35rem 0 0.2rem;">{{ $counts['in_review'] }}</div>
        <div style="font-size: 0.8rem; color: var(--slate-500);">Surgical committee review</div>
    </div>

    <!-- Approved -->
    <div class="card" style="padding: 1.25rem; border-left: 4px solid #6366f1;">
        <div style="font-size: 0.78rem; font-weight: 700; color: #4338ca; text-transform: uppercase; letter-spacing: 0.05em;">Approved</div>
        <div style="font-size: 2.15rem; font-weight: 800; color: var(--slate-900); margin: 0.35rem 0 0.2rem;">{{ $counts['approved'] }}</div>
        <div style="font-size: 0.8rem; color: var(--slate-500);">Cleared for manufacturing</div>
    </div>

    <!-- Completed -->
    <div class="card" style="padding: 1.25rem; border-left: 4px solid var(--success);">
        <div style="font-size: 0.78rem; font-weight: 700; color: #047857; text-transform: uppercase; letter-spacing: 0.05em;">Completed</div>
        <div style="font-size: 2.15rem; font-weight: 800; color: var(--slate-900); margin: 0.35rem 0 0.2rem;">{{ $counts['completed'] }}</div>
        <div style="font-size: 0.8rem; color: var(--slate-500);">Surgeries successfully executed</div>
    </div>
</div>

<!-- Recent Cases Quick View -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title" style="font-size: 1.05rem;">Recently Updated Cases</h2>
        <a href="{{ route('cases.index') }}" style="font-size: 0.85rem; color: var(--primary); font-weight: 600; text-decoration: none;">
            View full registry &rarr;
        </a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Case #</th>
                    <th>Patient Ref</th>
                    <th>Surgeon Name</th>
                    <th>Implant Type</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Surgery Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentCases as $case)
                <tr>
                    <td>
                        <strong style="color: var(--slate-900); font-family: monospace;">{{ $case->case_number }}</strong>
                    </td>
                    <td>
                        <span style="font-family: monospace; color: var(--slate-600);">{{ $case->patient_reference }}</span>
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
                    <td style="text-align: right;">
                        <a href="{{ route('cases.show', $case) }}" class="btn btn-secondary btn-sm">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 2rem; color: var(--slate-500);">
                        No cases found in database.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
