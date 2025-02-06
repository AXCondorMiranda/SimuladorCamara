<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'is_practice' => $this->is_practice,
            'test_type' => new TestTypeResource($this->whenLoaded('test_type')),
            // Agrega otros campos según sea necesario
        ];
    }
}
