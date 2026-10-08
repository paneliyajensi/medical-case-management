@csrf

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
    <!-- Case Number -->
    <div class="form-group">
        <label for="case_number" class="form-label">
            Case Number <span class="required">*</span>
        </label>
        <input type="text" 
               id="case_number" 
               name="case_number" 
               value="{{ old('case_number', $case->case_number) }}" 
               class="form-control {{ $errors->has('case_number') ? 'is-invalid' : '' }}" 
               placeholder="e.g. CASE-2001" 
               required>
        <span class="field-help">Unique tracking identifier.</span>
        @error('case_number')
            <span class="field-error">{{ $message }}</span>
        @enderror
    </div>

    <!-- Patient Reference (Dummy Code) -->
    <div class="form-group">
        <label for="patient_reference" class="form-label">
            Patient Reference <span class="required">*</span>
        </label>
        <input type="text" 
               id="patient_reference" 
               name="patient_reference" 
               value="{{ old('patient_reference', $case->patient_reference) }}" 
               class="form-control {{ $errors->has('patient_reference') ? 'is-invalid' : '' }}" 
               placeholder="e.g. PAT-8812" 
               required>
        <span class="field-help">Dummy code only (e.g., PAT-XXXX). Never use real names.</span>
        @error('patient_reference')
            <span class="field-error">{{ $message }}</span>
        @enderror
    </div>

    <!-- Surgeon Name -->
    <div class="form-group">
        <label for="surgeon_name" class="form-label">
            Surgeon Name <span class="required">*</span>
        </label>
        <input type="text" 
               id="surgeon_name" 
               name="surgeon_name" 
               value="{{ old('surgeon_name', $case->surgeon_name) }}" 
               class="form-control {{ $errors->has('surgeon_name') ? 'is-invalid' : '' }}" 
               placeholder="e.g. Dr. Sarah Jenkins" 
               required>
        @error('surgeon_name')
            <span class="field-error">{{ $message }}</span>
        @enderror
    </div>

    <!-- Implant Type -->
    <div class="form-group">
        <label for="implant_type" class="form-label">
            Implant Type <span class="required">*</span>
        </label>
        <input type="text" 
               id="implant_type" 
               name="implant_type" 
               value="{{ old('implant_type', $case->implant_type) }}" 
               class="form-control {{ $errors->has('implant_type') ? 'is-invalid' : '' }}" 
               placeholder="e.g. Titanium Hip Stem (Mod-4)" 
               required>
        @error('implant_type')
            <span class="field-error">{{ $message }}</span>
        @enderror
    </div>

    <!-- Status -->
    <div class="form-group">
        <label for="status" class="form-label">
            Lifecycle Status <span class="required">*</span>
        </label>
        <select id="status" 
                name="status" 
                class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" 
                required>
            <option value="">-- Select Status --</option>
            @foreach ($statuses as $statusOption)
                <option value="{{ $statusOption }}" 
                    {{ old('status', $case->status) === $statusOption ? 'selected' : '' }}>
                    {{ $statusOption }}
                </option>
            @endforeach
        </select>
        @error('status')
            <span class="field-error">{{ $message }}</span>
        @enderror
    </div>

    <!-- Priority -->
    <div class="form-group">
        <label for="priority" class="form-label">
            Priority <span class="required">*</span>
        </label>
        <select id="priority" 
                name="priority" 
                class="form-control {{ $errors->has('priority') ? 'is-invalid' : '' }}" 
                required>
            <option value="">-- Select Priority --</option>
            @foreach ($priorities as $priorityOption)
                <option value="{{ $priorityOption }}" 
                    {{ old('priority', $case->priority) === $priorityOption ? 'selected' : '' }}>
                    {{ $priorityOption }}
                </option>
            @endforeach
        </select>
        @error('priority')
            <span class="field-error">{{ $message }}</span>
        @enderror
    </div>

    <!-- Surgery Date (Optional) -->
    <div class="form-group">
        <label for="surgery_date" class="form-label">
            Surgery Date <span style="font-size: 0.8rem; font-weight: 400; color: var(--slate-500);">(Optional)</span>
        </label>
        <input type="date" 
               id="surgery_date" 
               name="surgery_date" 
               value="{{ old('surgery_date', $case->surgery_date ? ($case->surgery_date instanceof \Carbon\Carbon ? $case->surgery_date->format('Y-m-d') : substr($case->surgery_date, 0, 10)) : '') }}" 
               class="form-control {{ $errors->has('surgery_date') ? 'is-invalid' : '' }}">
        <span class="field-help">Target date for surgical procedure.</span>
        @error('surgery_date')
            <span class="field-error">{{ $message }}</span>
        @enderror

        <!-- Bonus: Public Holidays Reference from Nager.Date API -->
        @if (!empty($upcomingHolidays))
            <div style="margin-top: 0.6rem; padding: 0.5rem 0.75rem; background-color: var(--slate-100); border: 1px solid var(--slate-200); border-radius: var(--radius-sm); font-size: 0.775rem; color: var(--slate-700);">
                <div style="font-weight: 700; margin-bottom: 0.2rem; color: var(--primary-dark); display: flex; align-items: center; gap: 0.35rem;">
                    <span>📅</span> Upcoming Public Holidays (Nager.Date API):
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.15rem;">
                    @foreach ($upcomingHolidays as $holiday)
                        <div>
                            <strong>{{ $holiday['date'] ?? '' }}</strong> &mdash; {{ $holiday['name'] ?? '' }} ({{ $holiday['countryCode'] ?? '' }})
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<div style="margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--slate-200); display: flex; justify-content: flex-end; gap: 0.75rem;">
    <a href="{{ $case->exists ? route('cases.show', $case) : route('cases.index') }}" class="btn btn-secondary">
        Cancel
    </a>
    <button type="submit" class="btn btn-primary">
        {{ $case->exists ? 'Update Case' : 'Create Case' }}
    </button>
</div>
