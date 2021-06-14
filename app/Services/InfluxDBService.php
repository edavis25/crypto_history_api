<?php

namespace App\Services;

use App\Exceptions\InvalidInfluxDatabaseException;
use InfluxDB\Client;

/**
 * Service class for general interacts with InfluxDB. This class contains generic functionality
 * that is not tied to a specific measurement (e.g. listing all databases, measurements, etc.).
 */
class InfluxDBService
{
    /** @var \InfluxDB\Client */
    private $client;

    /**
     * Return listing of available InfluxDB databases.
     */
    public function showDatabases(): array
    {
        return $this->client()->listDatabases();
    }

    /**
     * List all measurements contained within a given database.
     *
     * @param string $database_name
     * @return array
     * @throws \Exception
     */
    public function measurementsFor(string $database_name): array
    {
        if (!$this->databaseExists($database_name)) {
            throw new InvalidInfluxDatabaseException;
        }

        $results = $this->client()
            ->selectDB($database_name)
            ->query('SHOW MEASUREMENTS')
            ->getPoints();

        return array_map(function ($item) {
            return $item['name'];
        }, $results);
    }

    /**
     * Determine if a given database exists (case-sensitive).
     *
     * @param $database_name
     * @return bool
     */
    public function databaseExists($database_name): bool
    {
        return in_array($database_name, $this->showDatabases());
    }

    /**
     * Return instance of the InfluxDB Client.
     *
     * @return \InfluxDB\Client
     */
    protected function client(): Client
    {
        if (!isset($this->client)) {
            $this->client = new Client(
                config('influxdb.host'),
                config('influxdb.port'),
                config('influxdb.username'),
                config('influxdb.password'),
                config('influxdb.ssl'),
                config('influxdb.verifySSL'),
                config('influxdb.timeout')
            );
        }

        return $this->client;
    }
}
