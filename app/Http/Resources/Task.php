<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\FolderCollection;

class Task extends JsonResource
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
			'title' => $this->title,
			'description' => $this->description,
			'due' => $this->when($this->due_at, (string)$this->due_at),
			'folders' => new FolderCollection($this->whenLoaded('folders')),
			'created' => (string)$this->created_at,
			'updated' => (string)$this->updated_at,
			'completed' => (string)$this->completed_at,
			'links' => [
				'self' => route('tasks.show', ['tasks' => $this->id]),
			],
		];
    }
}
