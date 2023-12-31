<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'expense';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'value' => $this->value,
            'date' => $this->date,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->user), //$this->whenLoaded('user')
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
