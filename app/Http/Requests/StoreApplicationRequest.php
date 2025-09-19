<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_location' => 'required|string',
            'apply_status' => 'nullable|string|max:255',
            'approval_status' => 'nullable|in:pending,accepted,rejected,ghosting',
            'application_category' => 'nullable|in:Internship,Full-time,Part-time,Contract',
            'notes' => 'nullable|string',
            'deadline' => 'nullable|date',
            'work_location' => 'nullable|string|max:255',
            'submitted_status' => 'nullable|in:submitted,not submitted',
        ];
    }
}
