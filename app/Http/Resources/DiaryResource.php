<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user->id,
            'title'      => $this->title,
            'content'    => $this->content,
            'entry_date' => $this->entry_date->format('Y-m-d'),
            'mood'       => $this->mood,
            'weather'    => $this->weather,
            'boolean'    => $this->is_favourite
        ];
    }
}
