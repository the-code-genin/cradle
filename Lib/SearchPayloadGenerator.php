<?php 

namespace Lib;

use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;

class SearchPayloadGenerator
{
    /**
     * Generate API payload.
     *
     * @param Request $request
     * @param Builder $results
     *
     * @return array
     */
    public static function generate(Request $request, $results): array
    {
        $queries = (object) $request->getQueryParams();
        $payload = [];

        if (is_int($queries->page) || is_int($queries->per_page)) { // If pagination is to be applied.
            $page = is_int($queries->page) ? $queries->page : 1;
            $perPage = is_int($queries->per_page) ? $queries->per_page : 10;

            /** @var Paginator */
            $results = $results->paginate($perPage, ['*'], 'results', $page);

            $payload = [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'prev_page' => ($results->currentPage() > 1) ? ($results->currentPage() - 1) : null,
                'next_page' => $results->hasMorePages() ? ($results->currentPage() + 1) : null,
                'from' => $results->firstItem(),
                'to' => $results->lastItem(),
                'data' => $results->items(),
            ];
        } else { // If all are to be gotten at once.
            $payload = [
                'data' => $results->get(),
                'total' => $results->count(),
            ];
        }

        return $payload;
    }
}
