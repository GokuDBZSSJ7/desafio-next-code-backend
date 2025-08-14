<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'service_type'   => $this->service_type,
            'scheduled_date' => $this->scheduled_date?->toDateTimeString(),
            'status'         => $this->status,
            'patient'        => new PatientResource($this->whenLoaded('patient')),
            'professional'   => new ProfessionalResource($this->whenLoaded('professional')),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
