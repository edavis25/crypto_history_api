<?php

namespace App\Services;

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
}
