@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div style="max-width: 500px; margin: 4rem auto; text-align: center;">
    <div class="card" style="padding: 2.5rem 2rem; box-shadow: var(--shadow-md);">
        <div style="font-size: 3.5rem; font-weight: 800; color: var(--primary); line-height: 1;">404</div>
        <h1 style="font-size: 1.4rem; font-weight: 700; color: var(--slate-900); margin: 0.75rem 0 0.5rem;">Page Not Found</h1>
        <p style="font-size: 0.9rem; color: var(--slate-500); margin-bottom: 1.5rem;">
            The requested page or case does not exist or may have been moved.
        </p>
        <div style="display: flex; gap: 0.75rem; justify-content: center;">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                Return to Dashboard
            </a>
            <a href="{{ route('login') }}" class="btn btn-secondary">
                Go to Sign In
            </a>
        </div>
    </div>
</div>
@endsection
