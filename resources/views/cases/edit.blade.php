@extends('layouts.app')

@section('title', 'Edit Case ' . $case->case_number)

@section('content')
<div style="max-width: 840px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 0.85rem; color: var(--slate-500); margin-bottom: 0.25rem;">
                <a href="{{ route('cases.index') }}" style="color: var(--primary); text-decoration: none;">&larr; Cases</a> /
                <a href="{{ route('cases.show', $case) }}" style="color: var(--primary); text-decoration: none;">{{ $case->case_number }}</a> / Edit
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--slate-900);">Edit Implant Case: {{ $case->case_number }}</h1>
        </div>
    </div>

    <div class="card" style="box-shadow: var(--shadow-md);">
        <div class="card-header">
            <h2 class="card-title" style="font-size: 1rem;">Update Case Specification</h2>
            <span style="font-size: 0.8rem; color: var(--slate-500);">Case ID #{{ $case->id }}</span>
        </div>
        <div class="card-body">
            <form action="{{ route('cases.update', $case) }}" method="POST">
                @method('PUT')
                @include('cases._form')
            </form>
        </div>
    </div>
</div>
@endsection
