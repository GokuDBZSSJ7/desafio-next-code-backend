<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            'patient_id'      => ['required', 'integer', 'exists:patients,id'],
            'professional_id' => ['required', 'integer', 'exists:professionals,id'],
            'service_type'    => ['required', 'string', 'max:100'],
            'scheduled_date'  => ['required', 'date'],
            'status'          => ['required', 'string', 'in:Agendado,Concluído,Cancelado'],
        ];
    }
}
