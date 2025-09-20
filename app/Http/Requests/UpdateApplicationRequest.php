<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'company_name' => 'sometimes|required|string|max:255',
            'company_location' => 'sometimes|required|string',
            'apply_status' => 'sometimes|string|max:255',
            'approval_status' => 'sometimes|in:pending,accepted,rejected,ghosting',
            'application_category' => 'sometimes|in:Internship,Full-time,Part-time,Contract',
            'notes' => 'nullable|string',
            'deadline' => 'nullable|date',
            'submitted_status' => 'sometimes|in:submitted,not submitted',
            'work_location' => 'sometimes|string|max:255',
        ];
    }
}
