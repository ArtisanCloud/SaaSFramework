<?php

namespace ArtisanCloud\SaaSFramework\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LandlordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
//        dd($this);
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'domain' => $this->domain,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
