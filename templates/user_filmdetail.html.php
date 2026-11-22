<div class="imdb-container">
    <div class="movie-header">
        <h1><?= htmlspecialchars($film['title']) ?> <span>(<?= htmlspecialchars($film['released_year']) ?>)</span></h1>
        <div class="genre-labels"><?= htmlspecialchars($film['genre_name']) ?></div>
        <?php if ($currentReviewerId !== null): ?>
            <form method="post" action="watchlist.php" class="watchlist-action">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['watchlist_csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="film_id" value="<?= (int) $film['id'] ?>">
                <input type="hidden" name="action" value="<?= $isInWatchlist ? 'remove' : 'add' ?>">
                <button type="submit" class="watchlist-button" aria-label="<?= $isInWatchlist ? 'Remove from watchlist' : 'Add to watchlist' ?>">
                    <?= $isInWatchlist ? '✓ Saved to Watchlist' : '＋ Add to Watchlist' ?>
                </button>
            </form>
        <?php endif; ?>
        <?php if ($watchlistNotice !== ''): ?>
            <p class="watchlist-notice"><?= htmlspecialchars($watchlistNotice, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
    </div>

    <div class="movie-main-content">
        <div class="poster-section">
            <img src="<?= htmlspecialchars($film['poster']) ?>" alt="<?= htmlspecialchars($film['title']) ?>">
            <div class="avg-score">
                <span style="color:var(--accent)">★</span> <?= number_format($stats['avg_rating'], 1) ?>/5
            </div>
        </div>

        <div class="details-section">
            <div class="info-box">
                <h3>Description</h3>
                <p><?= nl2br(htmlspecialchars($film['description'] ?: 'No description available for this movie.')) ?></p>
            </div>

            <div class="info-box">
                <p><strong>Director:</strong>
                    <span class="highlight">
                        <?= htmlspecialchars($film['director_name'] ?: 'Updating...') ?>
                    </span>
                </p>
                <hr>
                <p><strong>Cast:</strong>
                    <span class="highlight">
                        <?= htmlspecialchars($film['cast_names'] ?: 'Updating...') ?>
                    </span>
                </p>
            </div>

            <div class="reviews-section">
                <h3>User Reviews (<?= $stats['total_count'] ?>)</h3>
                <?php if (empty($reviews)): ?>
                    <p>No reviews yet. Be the first to review!</p>
                <?php else: ?>
                    <?php foreach ($reviews as $r): ?>
                        <div class="review-item">
                            <div class="rev-header">
                                <span class="rev-user"><?= htmlspecialchars($r['username']) ?></span>
                                <span class="rev-rating">⭐ <?= $r['rating'] ?>/5</span>
                            </div>
                            <span class="rev-date"><?= date('d M Y', strtotime($r['review_date'])) ?></span>
                            <p class="rev-text"><?= nl2br(htmlspecialchars($r['review_text'])) ?></p>
                            <?php if (!empty($r['image_path'])): ?>
                                <img class="review-meme-image" src="<?= htmlspecialchars($r['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="Image attached to review">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <?php if ($reviewPagination['totalPages'] > 1): ?>
                        <nav class="metadata-review-pagination" aria-label="Review pages">
                            <span>Page <?= $reviewPagination['currentPage'] ?> of <?= $reviewPagination['totalPages'] ?></span>
                            <?php if ($reviewPagination['currentPage'] > 1): ?>
                                <a href="filmdetail.php?id=<?= (int) $film['id'] ?>&amp;page=<?= $reviewPagination['currentPage'] - 1 ?>" aria-label="Previous reviews">&laquo; Previous</a>
                            <?php endif; ?>
                            <?php if ($reviewPagination['currentPage'] < $reviewPagination['totalPages']): ?>
                                <a href="filmdetail.php?id=<?= (int) $film['id'] ?>&amp;page=<?= $reviewPagination['currentPage'] + 1 ?>" aria-label="Next reviews">Next &raquo;</a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .imdb-container {
        color: white;
        padding: 20px;
    }

    .movie-header h1 span {
        font-weight: normal;
        color: #aaa;
    }

    .genre-labels {
        background: var(--accent);
        color: black;
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .movie-main-content {
        display: flex;
        gap: 40px;
        align-items: flex-start;
    }

    .poster-section img {
        width: 350px;
        border: 4px solid #333;
        border-radius: 8px;
    }

    .avg-score {
        font-size: 2rem;
        text-align: center;
        margin-top: 10px;
        font-weight: bold;
    }

    .details-section {
        flex: 1;
    }

    .info-box {
        background: #222;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .highlight {
        color: #5799ef;
    }

    .review-item {
        border-bottom: 1px solid #444;
        padding: 15px 0;
    }

    .rev-header {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
    }

    .rev-user {
        color: var(--accent);
    }

    .rev-date {
        font-size: 0.8rem;
        color: #888;
    }

    .rev-text {
        margin-top: 10px;
        line-height: 1.5;
    }

    .review-meme-image {
        display: block;
        width: min(100%, 420px);
        max-height: 420px;
        margin-top: 12px;
        border-radius: 8px;
        object-fit: contain;
    }

    .metadata-review-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
    }

    .metadata-review-pagination a {
        color: var(--accent);
        text-decoration: none;
    }
</style>