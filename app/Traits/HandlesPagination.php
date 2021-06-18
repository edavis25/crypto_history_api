<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HandlesPagination
{
    /**
     * Validate and return the number of results per_page with respect for max.
     *
     * @param Request $request
     * @return int
     */
    protected function perPage(Request $request): int
    {
        $default = config('api.pagination.default_per_page');
        $max = config('api.pagination.max_per_page');
        if ($request->per_page && $request->per_page > 0) {
            return (int) ($request->per_page <= $max) ? $request->per_page : $max;
        }

        return (int) $default;
    }

    /**
     * Determine offset for a paginated InfluxDB query.
     *
     * @param Request $request
     * @return int
     */
    protected function offset(Request $request): int
    {
        return ($request->page && $request->page > 1)
            ? ($request->page -1) * $request->per_page
            : 0;
    }
}
