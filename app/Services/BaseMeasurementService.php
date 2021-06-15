<?php

namespace App\Services;

use App\Contracts\InfluxDBMeasurement;
use Carbon\Carbon;
use TrayLabs\InfluxDB\Facades\InfluxDB;

/**
 * Abstract class that should be extended by other instantiatable services that are associated with
 * InfluxDB Measurements. Extending this class fills most requirements for the InfluxDBMeasurement contract.
 * The rest of the methods in this contract will need to be fulfilled by the extending children.
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
     * Default sort order for results.
     */
    const DEFAULT_SORT_ORDER = 'DESC';

    /**
     * {@inheritDoc}
     */
    public function getPairs(?int $limit = null, ?int $offset = null): array
    {
        $query = "SHOW TAG VALUES FROM {$this->measurement()} WITH KEY = \"{$this->tagKey()}\"";
        if ($limit) {
            $query .= " LIMIT $limit";
        }
        if ($offset) {
            $query .= " OFFSET $offset";
        }

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

    /**
     * {@inheritDoc}
     */
    public function isWhitelisted(): bool
    {
        return in_array($this->measurement(), config('influxdb.measurements'));
    }

    /**
     * Build query and return results for a specific trading pair.
     *
     * @param string $pair
     * @param array $filters
     * @return mixed
     */
    public function queryPair(string $pair, array $filters = []): array
    {
        $filters = array_merge($filters, [
            'pair' => $pair
        ]);

        return $this->query($filters);
    }

    /**
     * {@inheritDoc}
     *
     * @param array $filters
     * @return array
     */
    public function query(array $filters = []): array
    {
        $results = InfluxDB::getQueryBuilder()
            ->select("*")
            ->from($this->measurement())
            ->where($this->buildWhereConditions($filters))
            ->orderBy('time', $this->validOrder($filters['order'] ?? null));

        if ($limit = $filters['per_page'] ?? false) {
            $results->limit($limit);
        }
        if ($offset = $filters['offset'] ?? false) {
            $results->offset($offset);
        }

        return $results->getResultSet()->getPoints();
    }

    /**
     * Build supported conditions for the query's WHERE clause
     * formatted for the InfluxDB facade.
     *
     * @param array $data
     * @return array
     */
    protected function buildWhereConditions(array $data): array
    {
        $conditions = [];
        if ($pair = $data['pair'] ?? false) {
            array_push($conditions, "pair = '$pair'");
        }

        if ($after = $data['after'] ?? false) {
            $iso_date = Carbon::createFromTimestamp($after)->toISOString();
            array_push($conditions, "time >= '$iso_date'");
        }

        if ($before = $data['before'] ?? false) {
            $iso_date = Carbon::createFromTimestamp($before)->toISOString();
            array_push($conditions, "time <= '$iso_date'");
        }

        return $conditions;
    }

    /**
     * Validate and return sort order.
     *
     * @param string|null $sort_order
     * @return string
     */
    protected function validOrder(?string $sort_order = null): string
    {
        if ($sort_order && in_array(strtoupper($sort_order), ['ASC', 'DESC'])) {
            return strtoupper($sort_order);
        }

        return strtoupper(self::DEFAULT_SORT_ORDER);
    }
}
