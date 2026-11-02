<?php

class Pagination
{
    public const ITEMS_PER_PAGE = 10;

    public static function forRequest($totalItems, $requestedPage)
    {
        $pageValue = is_scalar($requestedPage) ? filter_var($requestedPage, FILTER_VALIDATE_INT) : false;
        $page = $pageValue === false ? 1 : max(1, (int) $pageValue);
        $totalPages = max(1, (int) ceil($totalItems / self::ITEMS_PER_PAGE));
        $page = min($page, $totalPages);

        return [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'itemsPerPage' => self::ITEMS_PER_PAGE,
            'offset' => ($page - 1) * self::ITEMS_PER_PAGE,
            'totalItems' => (int) $totalItems,
        ];
    }
}
