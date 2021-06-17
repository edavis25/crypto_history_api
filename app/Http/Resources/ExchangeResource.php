<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeResource extends JsonResource
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
            'name' => $this->resource['name'],
            'base_endpoint' => $this->resource['base_endpoint'],
            'last_price_recorded' => $this->resource['last_price_recorded'],
            'pairs' => $this->resource['pairs']
        ];
    }
}
