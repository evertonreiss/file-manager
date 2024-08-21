<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'mime_type' => $this->mine_type,
            'file_size' => $this->file_size,
            'description' => $this->description,
            'is_visible' => $this->is_visible,
            'is_downloadable' => $this->is_downloadable
        ];
    }
}
