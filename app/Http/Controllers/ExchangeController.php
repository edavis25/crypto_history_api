<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExchangeResource;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExchangeController extends Controller
{
    /**
     * List all of the exchanges available for querying (aka measurements in InfluxDB).
     *
     * @param Request $request
     *
     * @return ResourceCollection
     * @throws BindingResolutionException
     */
    public function index(Request $request): ResourceCollection
    {
        // todo scopes

        $exchanges = [];
        foreach (config('influxdb.measurements', []) as $key => $class) {
            /** @var \App\Contracts\InfluxDBMeasurement $influx_measurement_service */
            $influx_measurement_service = app()->make($class);

            $endpoint = array_search($key, config('influxdb.route_aliases', []));
            $endpoint = $endpoint ?: $key;

            array_push($exchanges, [
                'name'     => $influx_measurement_service->displayName(),
                'base_endpoint' => "/api/$endpoint/",
                'pairs' => $influx_measurement_service->getPairs()
            ]);
        }

        return ExchangeResource::collection($exchanges);
    }
}
