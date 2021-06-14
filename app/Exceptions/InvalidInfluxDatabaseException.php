<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InvalidInfluxDatabaseException extends Exception
{
    /**
     * {@inheritDoc}
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        if (!$message) {
            $message = 'Invalid InfluxDB database. Ensure database exists and name is correct (case-sensitive).';
        }
        parent::__construct($message, $code, $previous);
    }
}
