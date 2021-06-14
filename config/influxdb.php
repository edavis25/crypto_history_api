<?php

return [
    /*
    |--------------------------------------------------------------------------
    | InfluxDB Database Connection
    |--------------------------------------------------------------------------
    |
    | The network info and credentials needed to establish and
    | configure the connection to InfluxDB.
    |
    */
    'host' => env('INFLUXDB_HOST', 'localhost'),
    'port' => env('INFLUXDB_PORT', 8086),
    'username' => env('INFLUXDB_USER', ''),
    'password' => env('INFLUXDB_PASSWORD', ''),
    'ssl' => env('INFLUXDB_SSL', false),
    'verifySSL' => env('INFLUXDB_VERIFYSSL', false),
    'timeout' => env('INFLUXDB_TIMEOUT', 0),

    /*
    |--------------------------------------------------------------------------
    | Default Database
    |--------------------------------------------------------------------------
    |
    | Name of the database the InfluxDB facade uses for queries by default.
    |
    */
    'dbname' => env('INFLUXDB_DBNAME', ''),

    /*
    |--------------------------------------------------------------------------
    | UDP Settings
    |--------------------------------------------------------------------------
    |
    | Settings required when interacting with InfluxDB using UDP.
    |
    */
    'udp' => [
        'enabled' => env('INFLUXDB_UDP_ENABLED', false),
        'port'    => env('INFLUXDB_UDP_PORT', 8086),
    ],

    /*
    |--------------------------------------------------------------------------
    | Measurements
    |--------------------------------------------------------------------------
    |
    | White-listing of the measurements supported by the API. This array
    | should contain the names of measurements that exist within the default
    | Influx database. Whenever a new measurement is added at the data layer
    | its name will need to be registered here. Adding measurements here will
    | expose them for use in the API (all others will be hidden).
    |
    */
    'measurements' => [
        'poloniex' => \App\Services\PoloniexMeasurementService::class,

//        'cryptocompare_aggregate' => // todo
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Overrides
    |--------------------------------------------------------------------------
    |
    | Configure aliases for routes that map to InfluxDB measurements. This
    | is useful when creating a route associated with an InfluxDB measurement and
    | you do not want to use the measurement's name for the route. For example, if
    | InfluxDB had a measurement: "cryptocompare_aggregate", you could map an alias
    | for a prettier API route: "/cryptocompare/". Mappings are keyed by the alias
    | and contain a valid measurement name. The value is used to retrieve the driver,
    | so it MUST be registered in the 'measurements' array in this config file.
    | e.g. ['cryptocompare' => 'cryptocompare_aggregate']
    |
    */
    'route_aliases' => [
//        'cryptocompare' => 'cryptocompare_aggregate'
    ]
];
