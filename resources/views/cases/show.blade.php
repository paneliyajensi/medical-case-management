@extends('layouts.app')

@section('title', 'Case Details - ' . $case->case_number)

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <!-- Breadcrumbs & Header -->
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="font-size: 0.85rem; color: var(--slate-500); margin-bottom: 0.25rem;">
                <a href="{{ route('cases.index') }}" style="color: var(--primary); text-decoration: none;">&larr; Back to Case Registry</a>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <h1 style="font-size: 1.7rem; font-weight: 800; color: var(--slate-900);">{{ $case->case_number }}</h1>
                @php
                    $statusSlug = strtolower(str_replace(' ', '-', $case->status));
                @endphp
                <span class="status-badge status-{{ $statusSlug }}">
                    <span class="status-dot"></span>
                    {{ $case->status }}
                </span>
                <span class="priority-badge priority-{{ strtolower($case->priority) }}">
                    {{ $case->priority }} Priority
                </span>
            </div>
        </div>

        <!-- Action Buttons (Shown only to roles allowed to use them) -->
        <div style="display: flex; gap: 0.6rem;">
            @can('update', $case)
            <a href="{{ route('cases.edit', $case) }}" class="btn btn-secondary">
                Edit Case
            </a>
            @endcan

            @can('delete', $case)
            <form action="{{ route('cases.destroy', $case) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete case {{ $case->case_number }}? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    Delete Case
                </button>
            </form>
            @endcan
        </div>
    </div>

    <!-- Case Specification Grid -->
    <div class="card" style="margin-bottom: 1.5rem; box-shadow: var(--shadow-sm);">
        <div class="card-header">
            <h2 class="card-title" style="font-size: 1.05rem;">Clinical & Device Specification</h2>
            <span style="font-size: 0.8rem; color: var(--slate-500);">Record ID #{{ $case->id }}</span>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
                <div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em;">Case Number</div>
                    <div style="font-size: 1.05rem; font-weight: 700; color: var(--slate-900); font-family: monospace; margin-top: 0.25rem;">
                        {{ $case->case_number }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em;">Patient Reference</div>
                    <div style="font-size: 1.05rem; font-weight: 600; color: var(--slate-800); font-family: monospace; margin-top: 0.25rem;">
                        {{ $case->patient_reference }}
                    </div>
                    <span style="font-size: 0.75rem; color: var(--slate-500);">(Anonymized dummy patient identifier)</span>
                </div>

                <div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em;">Lead Operating Surgeon</div>
                    <div style="font-size: 1.05rem; font-weight: 600; color: var(--slate-900); margin-top: 0.25rem;">
                        {{ $case->surgeon_name }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em;">Implant Device Type</div>
                    <div style="font-size: 1.05rem; font-weight: 600; color: var(--slate-900); margin-top: 0.25rem;">
                        {{ $case->implant_type }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em;">Lifecycle Status</div>
                    <div style="margin-top: 0.35rem;">
                        <span class="status-badge status-{{ $statusSlug }}">
                            <span class="status-dot"></span>
                            {{ $case->status }}
                        </span>
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em;">Priority Classification</div>
                    <div style="margin-top: 0.35rem;">
                        <span class="priority-badge priority-{{ strtolower($case->priority) }}">
                            {{ $case->priority }}
                        </span>
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em;">Scheduled Surgery Date</div>
                    <div style="font-size: 1.05rem; font-weight: 600; color: var(--slate-800); margin-top: 0.25rem;">
                        @if ($case->surgery_date)
                            {{ $case->surgery_date->format('l, F j, Y') }}
                        @else
                            <span style="color: var(--slate-500); font-style: italic;">Not scheduled yet</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit & Provenance Information (Required: Show who created it and when) -->
    <div class="card" style="box-shadow: var(--shadow-sm); border-top: 3px solid var(--slate-700);">
        <div class="card-header">
            <h2 class="card-title" style="font-size: 1.05rem;">Case Provenance & Audit Trail</h2>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
                <div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em;">Created By</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: var(--slate-900); margin-top: 0.25rem;">
                        {{ $case->creator ? $case->creator->name : 'System Generated' }}
                    </div>
                    <div style="font-size: 0.8rem; color: var(--slate-500);">
                        {{ $case->creator ? $case->creator->email : 'N/A' }} 
                        @if ($case->creator)
                            &bull; <span class="role-badge {{ $case->creator->isAdmin() ? 'role-admin' : 'role-viewer' }}">{{ $case->creator->role }}</span>
                        @endif
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em;">Created At</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: var(--slate-800); margin-top: 0.25rem;">
                        {{ $case->created_at ? $case->created_at->format('M d, Y \a\t H:i:s') : 'N/A' }}
                    </div>
                    <div style="font-size: 0.8rem; color: var(--slate-500);">
                        {{ $case->created_at ? $case->created_at->diffForHumans() : '' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.775rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em;">Last Updated At</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: var(--slate-800); margin-top: 0.25rem;">
                        {{ $case->updated_at ? $case->updated_at->format('M d, Y \a\t H:i:s') : 'N/A' }}
                    </div>
                    <div style="font-size: 0.8rem; color: var(--slate-500);">
                        {{ $case->updated_at ? $case->updated_at->diffForHumans() : '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
