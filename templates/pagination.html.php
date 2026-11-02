<?php if ($pagination['totalItems'] > 0): ?>
    <?php
    $paginationUrl = static function ($page) {
        return '?' . htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $page])), ENT_QUOTES, 'UTF-8');
    };
    ?>
    <nav class="pagination" aria-label="Pagination">
        <span class="pagination-summary">
            Showing <?= $pagination['offset'] + 1 ?>–<?= min($pagination['offset'] + $pagination['itemsPerPage'], $pagination['totalItems']) ?> of <?= $pagination['totalItems'] ?>
        </span>
        <?php if ($pagination['currentPage'] > 1): ?>
            <a href="<?= $paginationUrl($pagination['currentPage'] - 1) ?>" aria-label="Previous page">&laquo; Previous</a>
        <?php endif; ?>

        <?php for ($page = 1; $page <= $pagination['totalPages']; $page++): ?>
            <?php if ($page === $pagination['currentPage']): ?>
                <span class="pagination-current" aria-current="page"><?= $page ?></span>
            <?php else: ?>
                <a href="<?= $paginationUrl($page) ?>"><?= $page ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
            <a href="<?= $paginationUrl($pagination['currentPage'] + 1) ?>" aria-label="Next page">Next &raquo;</a>
        <?php endif; ?>
    </nav>
    <style>
        .pagination {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 24px 0;
        }

        .pagination a,
        .pagination-current {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-decoration: none;
        }

        .pagination-current {
            background: #333;
            border-color: #333;
            color: #fff;
        }

        .pagination-summary {
            flex-basis: 100%;
            text-align: center;
        }
    </style>
        
<?php endif; ?>