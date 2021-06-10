<?php

namespace App\Services;

class PoloniexService extends BaseMeasurementService
{
    /**
     * {@inheritDoc}
     */
    public function measurement(): string
    {
        return 'poloniex';
    }
}
