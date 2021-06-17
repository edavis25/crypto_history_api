<?php

namespace App\Http\Controllers;

use App\Contracts\InfluxDBMeasurement;
use App\Http\Requests\ExchangePairRequest;
use App\Traits\HandlesPagination;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExchangePairController extends Controller
{
    use HandlesPagination;

    /**
     * @var \App\Contracts\InfluxDBMeasurement
     */
    protected $measurement_service;

    /**
     * Resolves the appropriate measurement service. The logic for resolving route to service happens in
     * the ExchangeServiceProvider. By injecting the interface here we are triggering the provider resolution.
     */
    public function __construct(InfluxDBMeasurement $measurement_service)
    {
        $this->measurement_service = $measurement_service;
    }

    /**
     * Return a listing of all historical data for a given exchange.
     *
     * @param ExchangePairRequest $request
     * @return JsonResource
     */
    public function index(ExchangePairRequest $request): JsonResource
    {
        // todo scopes
        // todo query params
        // todo time chunking
        // todo log request?

        // validates per_page against our max. added to request so JSON resource can use it.
        $request->request->add([
            'per_page' => $this->perPage($request)
        ]);

        $results = $this->measurement_service->query(array_merge($request->except('per_page'), [
            // query for 1 extra row so we can compare to per_page to determine if we have more pages.
            'per_page' => $request->per_page + 1,
            'offset' => $this->offset($request)
        ]));

        // if we have additional pages, we now remove the extra item we queried for.
        $has_next_page = count($results) > $request->per_page;
        if ($has_next_page) {
            array_pop($results);
        }

        return $this->measurement_service->buildResourceCollection($results, $has_next_page);
    }

    /**
     * Show historical data for a specific trading pair for a given exchange.
     *
     * @param ExchangePairRequest $request
     * @param string $exchange
     * @param string $pair
     * @return ResourceCollection
     */
    public function show(ExchangePairRequest $request, string $exchange, string $pair): ResourceCollection
    {
        // todo validation
        // todo scopes
        // todo log request?
        // todo query params
            // todo time chunking

        // validates per_page against our max. added to request so JSON resource can use it.
        $request->request->add([
            'per_page' => $this->perPage($request)
        ]);

         $results = $this->measurement_service->queryPair(
             $pair,
             array_merge($request->except('per_page'), [
                 // query for 1 extra row so we can compare to per_page to determine if we have more pages.
                 'per_page' => $request->per_page + 1,
                 'offset' => $this->offset($request)
             ])
         );

        // if we have additional pages, we now remove the extra item we queried for.
         $has_next_page = count($results) > $request->per_page;
         if ($has_next_page) {
             array_pop($results);
         }

        return $this->measurement_service->buildResourceCollection($results, $has_next_page);
    }
}
