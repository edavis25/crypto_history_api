<?php

namespace App\Services;

use App\Http\Resources\PoloniexMeasurement;
use App\Http\Resources\PoloniexMeasurementCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Instantiatable class for interacting with the "poloniex" measurement in InfluxDB.
 * This fulfills all requirements from the InfluxDBMeasurement contract and handles
 * any uniqueness in the data layer for the poloniex measurement.
 */
class PoloniexMeasurementService extends BaseMeasurementService
{
    /**
     * {@inheritDoc}
     */
    public function measurement(): string
    {
        return 'poloniex';
    }

    /**
     * {@inheritDoc}
     */
    public function displayName(): string
    {
        return 'Poloniex';
    }

    /**
     * {@inheritDoc}
     */
    public function buildResourceCollection(array $data, bool $has_next_page): ResourceCollection
    {
        return new PoloniexMeasurementCollection($data, $has_next_page);
    }

    /**
     * {@inheritDoc}
     */
    public function buildResource(array $data): JsonResource
    {
        return new PoloniexMeasurement($data);
    }
}
