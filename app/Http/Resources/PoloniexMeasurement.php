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
            'time'   => (string) $this->resource['time'],
            'pair'   => (string) $this->resource['pair'],
            'open'   => (float) $this->resource['open'],
            'close'  => (float) $this->resource['close'],
            'low'    => (float) $this->resource['low'],
            'high'   => (float) $this->resource['high'],
            'volume_from' => (float) $this->resource['volume'],
            'volume_to' => (float) $this->resource['quote_volume'],
        ];
    }
}
