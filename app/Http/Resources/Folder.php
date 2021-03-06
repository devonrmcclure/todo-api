<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TaskCollection;

class Folder extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'tasks' => new TaskCollection($this->whenLoaded('tasks')),
            'links' => [
                'self' => route('folders.show', ['folders' => $this->id])
            ]
        ];
    }
}
