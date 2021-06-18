<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CryptocompareMeasurement extends JsonResource
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
            'time'   => (string) $this->resource['time'],
            'pair'   => (string) $this->resource['pair'],
            'open'   => (float) $this->resource['open'],
            'close'  => (float) $this->resource['close'],
            'low'    => (float) $this->resource['low'],
            'high'   => (float) $this->resource['high'],
            'volume_from' => (float) $this->resource['volumefrom'],
            'volume_to' => (float) $this->resource['volumeto'],
        ];
    }
}
