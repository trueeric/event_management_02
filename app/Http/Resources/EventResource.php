<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        //* 自行定義回傳的內容陣列需要有哪些欄位
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'start_time'  => $this->start_time,
            'end_time'    => $this->end_time,
            'user'        => new UserResource($this->whenLoaded('user')),
            'attendees'   => AttendeeResource::collection($this->whenLoaded('attendees')),

        ];
    }
}
