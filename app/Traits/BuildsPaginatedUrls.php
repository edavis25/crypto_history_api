<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait BuildsPaginatedUrls
{
    /**
     * Rebuild URLs with query params and dynamic page numbers.
     *
     * @param Request $request
     * @param int|null $page_number
     * @return string
     */
    protected function buildUrlWithQueryParams(Request $request, ?int $page_number = null): string
    {
        $param_array = $request->except('page');

        if (!is_null($page_number)) {
            $param_array = array_merge($param_array, [
                'page' => $page_number
            ]);
        }

        return $request->fullUrlWithQuery($param_array);
    }

    /**
     * Return valid page number defaulting to 1.
     *
     * @param Request $request
     * @return int
     */
    protected function currentPage(Request $request): int
    {
        return (int) ($request->page ?? 1);
    }

    /**
     * Build URL for the next page.
     *
     * @param Request $request
     * @return string|null
     */
    protected function nextPage(Request $request): string
    {
        return $this->buildUrlWithQueryParams($request, $this->currentPage($request) + 1);
    }

    /**
     * Build URL for the previous page.
     *
     * @param Request $request
     * @return string
     */
    protected function previousPage(Request $request): string
    {
        return $this->buildUrlWithQueryParams($request, $this->currentPage($request) - 1);
    }
}
