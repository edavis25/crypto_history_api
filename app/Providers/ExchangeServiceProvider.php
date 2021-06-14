<?php

namespace App\Providers;

use App\Contracts\InfluxDBMeasurement;
use App\Exceptions\InfluxDBMeasurementDriverNotfoundException;
use App\Http\Controllers\ExchangePairController;
use App\Services\PoloniexMeasurementService;
use Illuminate\Support\ServiceProvider;

class ExchangeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Dynamically bind the appropriate measurement service for requests to the
         * ExchangePairController. When hitting these routes, we have various services
         * that implement the InfluxDBMeasurement contract mapped to each exchange we
         * are supporting in the API. The dynamic service resolution allows us to use
         * a route/single controller that can query historic pair data for any supported exchange.
         * e.g. "/poloniex/{endpoint}" resolves the PoloniexMeasurementService using config mappings.
         */

        $this->app->when(ExchangePairController::class)
            ->needs(
                InfluxDBMeasurement::class)
            ->give(function () {
                // this is a hack for fixing broken artisan commands. when app boots in the console, we don't have
                // the necessary request data to resolve a service dynamically, so we are just defaulting a working one.
                if ($this->app->runningInConsole()) {
                    return $this->app->make(PoloniexMeasurementService::class);
                }

                $measurement_driver_key = $this->app->request->route('exchange');

                // check for aliases to the route parameter path names. these aliases are used
                // when the routes path params are different from InfluxDB measurement names.
                if (array_key_exists($measurement_driver_key, config('influxdb.route_aliases'))) {
                    $measurement_driver_key = config("influxdb.route_aliases.{$measurement_driver_key}");
                }

                // retrieve the full class namespace for the
                $driver_class = config("influxdb.measurements.{$measurement_driver_key}");
                if (!$driver_class) {
                    // this will return a 404 response on Ajax requests for routes without drivers.
                    throw new InfluxDBMeasurementDriverNotfoundException($measurement_driver_key);
                }

                return $this->app->make($driver_class);
            });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
