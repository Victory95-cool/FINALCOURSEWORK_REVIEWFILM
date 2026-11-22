<?php

class Pagination
{
    public const ITEMS_PER_PAGE = 10;

    public function forRequest($totalItems, $requestedPage, $itemsPerPage = self::ITEMS_PER_PAGE)
    {
        $itemsPerPage = max(1, (int) $itemsPerPage);
        $pageValue = is_scalar($requestedPage) ? filter_var($requestedPage, FILTER_VALIDATE_INT) : false;
        $page = $pageValue === false ? 1 : max(1, (int) $pageValue);
        $totalPages = max(1, (int) ceil($totalItems / $itemsPerPage));
        $page = min($page, $totalPages);

        return [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'itemsPerPage' => $itemsPerPage,
            'offset' => ($page - 1) * $itemsPerPage,
            'totalItems' => (int) $totalItems,
        ];
    }
}
