<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            "details" => [
                'name'    => $this->name,
                'contact' => $this->contact,
                'email'   => $this->email,
                'address' => $this->address,
                'address2' => $this->address2,
                'zip'     => $this->zip,
                'created_at' => $this->created_at->toDateTimeString(),
            ],

        ];
    }
}
