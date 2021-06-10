<?php

namespace App\Services;

use App\Contracts\InfluxDBMeasurement;
use TrayLabs\InfluxDB\Facades\InfluxDB;

/**
 * Abstract class that should be extended by other instantiatable services that are associated with
 * influxDB Measurements. Extending this class partially fulfills requirements for the InfluxDBMeasurement.
 * Interface. The rest of the methods in this contract will need to be fulfilled by the extending children.
 *
 * e.g. the PoloniexService interacts exclusively with the "poloniex" measurement for historical price data.
 */
abstract class BaseMeasurementService implements InfluxDBMeasurement
{
    /**
     * Default InfluxDB Tag key used by the measurement for tracking price data for trading pair.
     */
    const DEFAULT_TAG_KEY = 'pair';

    /**
     * {@inheritDoc}
     */
    public function getPairs(): array
    {
        $query = "SHOW TAG VALUES FROM {$this->measurement()} WITH KEY = \"{$this->tagKey()}\"";
        $results = InfluxDB::query($query)
            ->getPoints();

        return array_map(function ($item) {
            return $item['value'];
        }, $results);
    }

    /**
     * {@inheritDoc}
     */
    public function tagKey(): string
    {
        return self::DEFAULT_TAG_KEY;
    }
}
