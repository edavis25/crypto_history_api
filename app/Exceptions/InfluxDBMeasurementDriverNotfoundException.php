<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InfluxDBMeasurementDriverNotfoundException extends Exception
{
    /**
     * InfluxDBMeasurementDriverNotfoundException constructor.
     *
     * @param string $driver_name
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($driver_name, $code = 0, Throwable $previous = null)
    {
        $message = "Could not find measurement driver for: '$driver_name'. Make sure to register it in the measurements array in the InfluxDB config.";
        parent::__construct($message, $code, $previous);
    }

    /**
     * Defines how the exception should be rendered. If this exception is thrown
     * during the course of a request to our API, we will return a 404 response.
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json(['error' => 'Exchange not found.'], 404);
    }
}
