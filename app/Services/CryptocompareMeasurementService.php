<?php

namespace App\Services;

use App\Http\Resources\CryptocompareMeasurement;
use App\Http\Resources\CryptocompareMeasurementCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CryptocompareMeasurementService extends BaseMeasurementService
{
    /**
     * {@inheritDoc}
     */
    public function measurement(): string
    {
        return 'cryptocompare_aggregate';
    }

    /**
     * {@inheritDoc}
     */
    public function displayName(): string
    {
        return 'CryptoCompare';
    }

    /**
     * {@inheritDoc}
     */
    public function buildResource(array $data): JsonResource
    {
        return new CryptocompareMeasurement($data);
    }

    /**
     * {@inheritDoc}
     */
    public function buildResourceCollection(array $data, bool $has_next_page): ResourceCollection
    {
        return new CryptocompareMeasurementCollection($data, $has_next_page);
    }
}
