<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Pagination settings for API request queries. InfluxDB has a default
    | max of 10,000 results per query, so we set our max to be 1 less. We are
    | using cursor-based pagination and we will always query for 1 extra record
    | than is being asked for so we can determine if we have additional pages.
    |
    */

    'pagination' => [
        'default_per_page' => 1000,
        'max_per_page' => 9999
    ],

];
