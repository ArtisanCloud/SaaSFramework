<?php

namespace ArtisanCloud\SaaSFramework\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class BasicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $arrayVisible = $this->resource->toArray();
//        dump($arrayVisible);
        $arrayTransformedKeys = transformArrayKeysToCamel($arrayVisible);

        return $arrayTransformedKeys;
    }

}
