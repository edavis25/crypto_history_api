<?php

namespace App\Contracts;

interface InfluxDBMeasurement
{
    /**
     * Return the name of the associated InfluxDB measurement.
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
     * @return array
     */
    public function getPairs(): array;

    /**
     * Return the default key for the InfluxDB Measurement's Tag.
     * e.g. the poloniex measurement uses the "pair" tag for tracking historical price data.
     *
     * @return string
     */
    public function tagKey(): string;
}
