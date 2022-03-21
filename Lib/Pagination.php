<?php

namespace Lib;

use Lib\PageOffsetAndLimit;

class Pagination
{
    /**
     * Generate API payload.
     *
     * @param Request $request
     * @param Builder $results
     *
     * @return PageOffsetAndLimit
     */
    public static function calculatePageOffsetAndLimit(
        int $total,
        int $page,
        int $perPage
    ) {
        if ($total == 0) { // No results
            return new PageOffsetAndLimit(0, $perPage);
        }

        // Fix per page variable
        if ($perPage < 1) $perPage = 10;

        // Get the total number of pages
        $totalPages = ceil($total / $perPage);

        // Fix the page variable
        if ($page > $totalPages) $page = $totalPages;
        else if ($page < 1) $page = 1;

        // Calculate the offset to start fetching records from.
        $offset = (($page - 1) * $perPage);

        // Response
        return new PageOffsetAndLimit($offset, $offset + $perPage);
    }
}
