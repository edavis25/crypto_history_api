<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PoloniexMeasurement extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'time'   => $this->resource['time'],
            'open'   => $this->resource['open'],
            'close'  => $this->resource['close'],
            'low'    => $this->resource['low'],
            'high'   => $this->resource['high'],
            'pair'   => $this->resource['pair'],
            'volume' => $this->resource['volume'],
        ];
    }
}
