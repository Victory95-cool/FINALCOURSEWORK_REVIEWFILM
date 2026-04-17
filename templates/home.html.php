<h2>Explore More Movies</h2>
<br />

<div class="slideshow">
    <div class="image-track">
        <?php if (!empty($films)): ?>
            <?php
            for ($i = 0; $i < 3; $i++):
                foreach ($films as $film):
                    $stats = getFilmStats($pdo, $film['id']);
            ?>
                    <div class="poster-wrapper">
                        <a href="filmdetail.php?id=<?= $film['id'] ?>">
                            <img src="<?= htmlspecialchars($film['poster']) ?>"
                                alt="<?= htmlspecialchars($film['title']) ?>">

                            <div class="poster-overlay">
                                <div class="overlay-content">
                                    <span class="stars">⭐ <?= number_format($stats['avg_rating'], 1) ?></span>
                                    <span class="review-count"><?= $stats['total_count'] ?> Reviews</span>
                                </div>
                            </div>
                        </a>
                    </div>
            <?php
                endforeach;
            endfor;
            ?>
        <?php else: ?>
            <p>No movies available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<style>
    :root {
        --accent: #f5c518;
    }

    .slideshow {
        width: 100%;
        height: fit-content;
        overflow: visible;
    }

    .image-track {
        width: fit-content;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: slide 40s linear infinite;
    }

    .image-track:hover {
        animation-play-state: paused;
    }

    .poster-wrapper {
        position: relative;
        margin: 0 25px;
        transition: transform 0.3s ease;
    }

    .poster-wrapper img {
        width: 270px;
        height: 400px;
        display: block;
        cursor: pointer;
        z-index: 1;
    }

    .poster-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding-top: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
        pointer-events: none;
    }

    .overlay-content {
        text-align: center;
        color: white;
        font-weight: bold;
    }

    .overlay-content .stars {
        display: block;
        font-size: 1.5rem;
        color: var(--accent);
    }

    .poster-wrapper:hover {
        transform: scale(1.15);
        z-index: 999;
    }

    .poster-wrapper:hover .poster-overlay {
        opacity: 1;
    }

    .poster-wrapper:hover img {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        border: 3px solid var(--accent);
    }

    @keyframes slide {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(-33.3333%);
        }
    }
</style>