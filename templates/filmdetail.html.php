<div class="imdb-container">
    <div class="movie-header">
        <h1><?= htmlspecialchars($film['title']) ?> <span>(<?= htmlspecialchars($film['released_year']) ?>)</span></h1>
        <div class="genre-labels"><?= htmlspecialchars($film['genre_name']) ?></div>
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
                        </div>
                    <?php endforeach; ?>
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
</style>