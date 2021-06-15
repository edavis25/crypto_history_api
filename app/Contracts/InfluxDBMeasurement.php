<?php

namespace App\Contracts;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

interface InfluxDBMeasurement
{
    /**
     * The name of the associated InfluxDB measurement.
     * @note this maps a service's "table" name (InfluxDB calls them measurements).
     * e.g. "poloniex" would be used to query historical data for that exchange.
     *
     * @return string
     */
    public function measurement(): string;

    /**
     * Return a listing of the distinct crypto pairs associated with the measurement.
     * e.g. ['btc_eth', 'usd_btc', 'eth_doge']
     *
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getPairs(?int $limit = null, ?int $offset = null): array;

    /**
     * Return the default key for the InfluxDB Measurement's Tag.
     * e.g. the poloniex measurement uses the "pair" tag for tracking
     * historical price data.
     *
     * @return string
     */
    public function tagKey(): string;

    /**
     * Human-friendly name for the measurement.
     *
     * @return string
     */
    public function displayName(): string;

    /**
     * The measurement name is white-listed to be used in the API.
     * To white-list a measurement, add it to the "measurements"
     * key in the influxdb.php config.
     *
     * @return bool
     */
    public function isWhitelisted(): bool;

    /**
     * Perform a paginated query on the associated measurement.
     *
     * @param array $filters
     * @return mixed
     */
    public function query(array $filters = []): array;

    /**
     * Query and return data for a given pair.
     *
     * @param string $pair
     * @param array $filters
     * @return array
     */
    public function queryPair(string $pair, array $filters = []): array;

    /**
     * Build a singular JSON resource for a given record. Each measurement
     * can potentially have different data schemas and each individual service
     * is responsible for building a standardized response with its specific schema.
     *
     * @param array $data
     * @return JsonResource
     */
    public function buildResource(array $data): JsonResource;

    /**
     * Builds a collection of JSON resources for the associated measurement.
     *
     * @param array $data
     * @param bool $has_next_page
     *
     * @return ResourceCollection
     */
    public function buildResourceCollection(array $data, bool $has_next_page): ResourceCollection;
}
