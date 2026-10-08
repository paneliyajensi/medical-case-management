<?php

namespace App\Http\Requests;

use App\Models\MedicalCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $case = $this->route('case') ?? $this->route('id');
        $caseModel = $case instanceof MedicalCase ? $case : MedicalCase::find($case);

        if (!$caseModel) {
            return true; // Let controller handle 404
        }

        return $this->user() !== null && $this->user()->can('update', $caseModel);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $case = $this->route('case') ?? $this->route('id');
        $caseId = $case instanceof MedicalCase ? $case->id : $case;

        return [
            'case_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('cases', 'case_number')->ignore($caseId),
            ],
            'patient_reference' => ['required', 'string', 'max:100'],
            'surgeon_name' => ['required', 'string', 'max:100'],
            'implant_type' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', Rule::in(MedicalCase::STATUSES)],
            'priority' => ['required', 'string', Rule::in(MedicalCase::PRIORITIES)],
            'surgery_date' => ['nullable', 'date'],
        ];
    }

    /**
     * Custom validation messages for field-level feedback.
     */
    public function messages(): array
    {
        return [
            'case_number.required' => 'Case Number is required.',
            'case_number.unique' => 'This Case Number has already been registered.',
            'patient_reference.required' => 'Patient Reference dummy code is required.',
            'surgeon_name.required' => 'Surgeon Name is required.',
            'implant_type.required' => 'Implant Type is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: ' . implode(', ', MedicalCase::STATUSES) . '.',
            'priority.required' => 'Priority is required.',
            'priority.in' => 'Priority must be one of: ' . implode(', ', MedicalCase::PRIORITIES) . '.',
            'surgery_date.date' => 'Surgery Date must be a valid date.',
        ];
    }
}
