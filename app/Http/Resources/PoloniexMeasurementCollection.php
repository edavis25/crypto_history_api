<?php

namespace App\Http\Resources;

use App\Traits\BuildsPaginatedUrls;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PoloniexMeasurementCollection extends ResourceCollection
{
    use BuildsPaginatedUrls;

    /**
     * @var bool - flag used for generating next/previous pagination.
     */
    protected $has_next_page;

    /**
     * PoloniexMeasurementCollection constructor.
     *
     * @param $data
     * @param bool $has_next_page
     */
    public function __construct($data, bool $has_next_page)
    {
        $this->has_next_page = $has_next_page;
        parent::__construct($data);
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => PoloniexMeasurement::collection($this->collection),
            'links' => [
                'prev' => ($this->currentPage($request) > 1) ? $this->previousPage($request) : null,
                'next' => $this->has_next_page ? $this->nextPage($request) : null,
                'current' => $this->buildUrlWithQueryParams($request, $this->currentPage($request))
            ],
            'meta' => [
                'current_page' => $this->currentPage($request),
                'per_page' => (int) $request->per_page,
                'max_per_page' => (int) config('api.pagination.max_per_page'),
            ]
        ];
    }
}
