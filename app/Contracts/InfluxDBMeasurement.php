<?php

namespace App\Contracts;

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
}
