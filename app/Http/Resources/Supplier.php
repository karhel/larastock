<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Supplier extends JsonResource
{
    /**
     * The "data" wrapper that should be applied
     */
    public static $wrap = 'supplier';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
