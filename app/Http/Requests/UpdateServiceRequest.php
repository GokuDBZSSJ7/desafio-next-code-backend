<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
            'patient_id'      => ['sometimes', 'integer', 'exists:patients,id'],
            'professional_id' => ['sometimes', 'integer', 'exists:professionals,id'],
            'service_type'    => ['sometimes', 'string', 'max:100'],
            'scheduled_date'  => ['sometimes', 'date'],
            'status'          => ['sometimes', 'string', 'in:Agendado,Concluído,Cancelado'],
        ];
    }
}
