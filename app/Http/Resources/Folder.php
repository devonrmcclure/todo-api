<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
			'links' => [
				'self' => route('folders.show', ['folders' => $this->id])
			]
		];
    }
}
