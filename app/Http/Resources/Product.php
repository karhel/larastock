<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * The "data" wrapper that should be applied
     */
    public static $wrap = 'product';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'quantity' => $this->whenPivotLoaded('order_product', function() {
                return $this->pivot->quantity;
            }),

            'unit_price' => $this->whenPivotLoaded('order_product', function() {
                return $this->pivot->unit_price;
            }),
        ];
    }
}
