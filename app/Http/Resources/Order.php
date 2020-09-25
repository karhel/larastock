<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    /**
     * The "data" wrapper that should be applied
     */
    public static $wrap = 'order';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'number'    => $this->number,
            'status'    => $this->status,
            'supplier'  => Supplier::make($this->supplier),
            'products'  => Product::collection(
                $this->whenLoaded('products'))
        ];
    }
}
