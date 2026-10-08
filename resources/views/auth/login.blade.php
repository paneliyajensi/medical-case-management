@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div style="max-width: 440px; margin: 3rem auto;">
    <div class="card" style="box-shadow: var(--shadow-md);">
        <div style="padding: 2rem 2rem 1.5rem; text-align: center; border-bottom: 1px solid var(--slate-200); background: linear-gradient(180deg, #f8fafc, #ffffff);">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary), #0284c7); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 1.6rem; font-weight: 800; margin-bottom: 0.75rem;">+</div>
            <h1 style="font-size: 1.4rem; font-weight: 700; color: var(--slate-900);">MedCase Portal</h1>
            <p style="font-size: 0.875rem; color: var(--slate-500); margin-top: 0.25rem;">Medical Device Case Management</p>
        </div>

        <div class="card-body" style="padding: 2rem;">
            @if ($errors->has('email'))
                <div class="alert alert-error" style="margin-bottom: 1.25rem; font-size: 0.85rem;">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                           placeholder="name@medcase.local" 
                           required 
                           autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password <span class="required">*</span></label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="••••••••" 
                           required>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.45rem; font-size: 0.85rem; color: var(--slate-600); cursor: pointer;">
                        <input type="checkbox" name="remember" value="1">
                        Remember session
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.95rem;">
                    Sign In
                </button>
            </form>

            <div style="margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1px dashed var(--slate-200);">
                <div style="font-size: 0.78rem; font-weight: 600; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; text-align: center;">
                    Quick Demo Credentials (Click to fill)
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem;">
                    <button type="button" 
                            onclick="fillCredentials('admin@medcase.local', 'password123')"
                            class="btn btn-secondary btn-sm"
                            style="font-size: 0.78rem; text-align: center; border-color: var(--primary);">
                        <strong style="color: var(--primary);">Admin</strong> (Full CRUD)
                    </button>
                    <button type="button" 
                            onclick="fillCredentials('viewer@medcase.local', 'password123')"
                            class="btn btn-secondary btn-sm"
                            style="font-size: 0.78rem; text-align: center;">
                        <strong style="color: var(--slate-700);">Viewer</strong> (Read Only)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fillCredentials(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection
