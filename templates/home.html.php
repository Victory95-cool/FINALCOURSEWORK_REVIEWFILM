<?php $homeFeaturedFilms = array_slice($films, 0, 4); ?>

<section class="home-intro" id="home-intro" aria-labelledby="home-intro-title">
    <div class="home-intro__copy">
        <div class="home-intro__badges">
            <span class="home-intro__badge home-intro__badge--accent">★ FILM REVIEW COMMUNITY</span>
            <span class="home-intro__badge">Discover · Rate · Review</span>
        </div>
        <h2 class="home-intro__title" id="home-intro-title">Find your next film. Share your point of view.</h2>
        <p class="home-intro__description">
            Your ultimate movie hub is here! Find exactly what to watch by searching titles, directors, or genres. Explore real ratings, box office trends, and curated recommendations, then read the community reviews and share your own hot take.
        </p>
        <div class="home-intro__actions">
            <a class="home-intro__link home-intro__link--primary" href="films.php">
                Explore films <span aria-hidden="true">&rarr;</span>
            </a>
            <a class="home-intro__link home-intro__link--secondary" href="review.php">
                Read reviews
            </a>
        </div>
        <p class="home-intro__note">
            Ready to write a review? <a href="addreview.php">Share it here</a>, or <a href="contact_admin.php">contact the admin</a> for help.
        </p>
    </div>

    <aside class="home-intro__showcase" aria-label="Recently added films">
        <div class="home-intro__showcase-header">
            <h3>Recently added</h3>
            <span><?= count($homeFeaturedFilms) ?> films</span>
            <?php if (count($homeFeaturedFilms) > 1): ?>
                <div class="home-intro__controls" aria-label="Featured film controls">
                    <button type="button" data-gallery-direction="-1" aria-label="Previous film">&lsaquo;</button>
                    <button type="button" data-gallery-direction="1" aria-label="Next film">&rsaquo;</button>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($homeFeaturedFilms)): ?>
            <div class="home-intro__stage">
                <?php foreach ($homeFeaturedFilms as $index => $featuredFilm): ?>
                    <?php
                    $featuredStats = $featuredFilm['stats'] ?? [];
                    $featuredPoster = $featuredFilm['poster'] ?? '';
                    ?>
                    <a
                        class="home-intro__slide<?= $index === 0 ? ' is-active' : '' ?>"
                        href="filmdetail.php?id=<?= (int) $featuredFilm['id'] ?>"
                        data-gallery-slide
                        data-gallery-index="<?= $index ?>"
                        aria-label="View <?= htmlspecialchars($featuredFilm['title'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                        <?php if ($featuredPoster !== ''): ?>
                            <img
                                class="home-intro__backdrop"
                                src="<?= htmlspecialchars($featuredPoster, ENT_QUOTES, 'UTF-8') ?>"
                                alt=""
                                aria-hidden="true"
                                loading="lazy"
                            >
                            <img
                                class="home-intro__poster"
                                src="<?= htmlspecialchars($featuredPoster, ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($featuredFilm['title'], ENT_QUOTES, 'UTF-8') ?>"
                                loading="<?= $index === 0 ? 'eager' : 'lazy' ?>"
                            >
                        <?php else: ?>
                            <span class="home-intro__poster-placeholder" aria-hidden="true">No poster available</span>
                        <?php endif; ?>
                        <span class="home-intro__image-shade" aria-hidden="true"></span>
                        <span class="home-intro__film-info">
                            <span class="home-intro__film-meta">
                                <span><?= htmlspecialchars($featuredFilm['genre'] ?? 'Film', ENT_QUOTES, 'UTF-8') ?></span>
                                <?php if (!empty($featuredFilm['released_year'])): ?>
                                    <span><?= htmlspecialchars($featuredFilm['released_year'], ENT_QUOTES, 'UTF-8') ?></span>
                                <?php endif; ?>
                            </span>
                            <strong><?= htmlspecialchars($featuredFilm['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <span class="home-intro__film-rating">
                                <?php if ((int) ($featuredStats['total_count'] ?? 0) > 0): ?>
                                    <span>★ <?= number_format((float) $featuredStats['avg_rating'], 1) ?>/5</span>
                                    <span><?= (int) $featuredStats['total_count'] ?> <?= (int) $featuredStats['total_count'] === 1 ? 'review' : 'reviews' ?></span>
                                <?php else: ?>
                                    <span>Be the first to review</span>
                                <?php endif; ?>
                            </span>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if (count($homeFeaturedFilms) > 1): ?>
                <div class="home-intro__thumbnails" aria-label="Choose a featured film">
                    <?php foreach ($homeFeaturedFilms as $index => $featuredFilm): ?>
                        <button
                            class="home-intro__thumbnail<?= $index === 0 ? ' is-active' : '' ?>"
                            type="button"
                            data-gallery-thumbnail="<?= $index ?>"
                            aria-label="Show <?= htmlspecialchars($featuredFilm['title'], ENT_QUOTES, 'UTF-8') ?>"
                            aria-pressed="<?= $index === 0 ? 'true' : 'false' ?>"
                        >
                            <?php if (!empty($featuredFilm['poster'])): ?>
                                <img
                                    src="<?= htmlspecialchars($featuredFilm['poster'], ENT_QUOTES, 'UTF-8') ?>"
                                    alt=""
                                    loading="lazy"
                                >
                            <?php else: ?>
                                <span aria-hidden="true">No poster</span>
                            <?php endif; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="home-intro__empty">
                <p>No films are available yet.</p>
                <a href="films.php">Browse films</a>
            </div>
        <?php endif; ?>
    </aside>
</section>

<h2>Explore More Movies</h2>
<br />

<div class="slideshow">
    <div class="image-track">
        <?php if (!empty($films)): ?>
            <?php
            for ($i = 0; $i < 3; $i++):
                foreach ($films as $film):
            ?>
                    <div class="poster-wrapper">
                        <a href="filmdetail.php?id=<?= $film['id'] ?>">
                            <img src="<?= htmlspecialchars($film['poster']) ?>"
                                alt="<?= htmlspecialchars($film['title']) ?>">

                            <div class="poster-overlay">
                                <div class="overlay-content">
                                    <span class="stars">⭐ <?= number_format($film['stats']['avg_rating'], 1) ?></span>
                                    <span class="review-count"><?= $film['stats']['total_count'] ?> Reviews</span>
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

    /* Homepage hero and database-backed film showcase */
    #home-intro {
        --home-hero-accent: #f5c518;
        width: auto;
        max-width: none;
        min-height: 440px;
        margin: 0 0 52px;
        padding: clamp(20px, 3vw, 38px);
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
        align-items: stretch;
        gap: clamp(20px, 3vw, 38px);
        overflow: hidden;
        isolation: isolate;
        color: #fff;
        background:
            radial-gradient(ellipse at 0% 0%, rgba(245, 197, 24, 0.13), transparent 46%),
            linear-gradient(135deg, #191919 0%, #111 60%, #090909 100%);
        border: 1px solid rgba(255, 255, 255, 0.09);
        border-radius: 16px;
        box-shadow: 0 18px 48px rgba(0, 0, 0, 0.34);
        font-family: "Roboto", Arial, sans-serif;
        line-height: 1.5;
    }

    #home-intro *,
    #home-intro *::before,
    #home-intro *::after {
        box-sizing: border-box;
    }

    #home-intro .home-intro__copy {
        min-width: 0;
        align-self: center;
    }

    #home-intro .home-intro__badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }

    #home-intro .home-intro__badge {
        display: inline-flex;
        align-items: center;
        min-height: 26px;
        padding: 4px 9px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 5px;
        background: #252525;
        color: #dedede;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        line-height: 1.2;
    }

    #home-intro .home-intro__badge--accent {
        border-color: transparent;
        background: var(--home-hero-accent);
        color: #111;
    }

    #home-intro .home-intro__title {
        margin: 0 0 14px;
        padding: 0;
        color: #fff;
        font-family: "Roboto", Arial, sans-serif;
        font-size: clamp(2rem, 3.2vw, 3.2rem);
        font-weight: 800;
        letter-spacing: -0.035em;
        line-height: 1.06;
        text-shadow: none;
        animation: none;
    }

    #home-intro .home-intro__title::after {
        content: none;
    }

    #home-intro .home-intro__description {
        max-width: 590px;
        margin: 0;
        color: #c9c9c9;
        font-size: clamp(0.94rem, 1.2vw, 1.04rem);
        font-weight: 400;
        line-height: 1.7;
        animation: none;
    }

    #home-intro .home-intro__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 23px;
    }

    #home-intro .home-intro__link {
        display: inline-flex;
        width: auto;
        min-height: 44px;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 10px 17px;
        border: 1px solid transparent;
        border-radius: 7px;
        font-size: 0.86rem;
        font-weight: 700;
        line-height: 1.2;
        text-decoration: none;
        transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
    }

    #home-intro .home-intro__link--primary {
        background: var(--home-hero-accent);
        color: #111;
    }

    #home-intro .home-intro__link--primary:hover {
        background: #ffda4a;
        color: #111;
        transform: translateY(-2px);
    }

    #home-intro .home-intro__link--secondary {
        border-color: rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.04);
        color: #fff;
    }

    #home-intro .home-intro__link--secondary:hover {
        border-color: rgba(245, 197, 24, 0.65);
        background: rgba(245, 197, 24, 0.08);
        color: #fff;
        transform: translateY(-2px);
    }

    #home-intro .home-intro__note {
        margin: 18px 0 0;
        color: #a9a9a9;
        font-size: 0.82rem;
        font-weight: 400;
        line-height: 1.55;
        animation: none;
    }

    #home-intro .home-intro__note a {
        color: var(--home-hero-accent);
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    #home-intro .home-intro__showcase {
        min-width: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 11px;
        background: #111;
        box-shadow: 0 14px 36px rgba(0, 0, 0, 0.35);
    }

    #home-intro .home-intro__showcase-header {
        min-height: 43px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.09);
        background: #171717;
    }

    #home-intro .home-intro__showcase-header h3 {
        margin: 0;
        color: #fff;
        font-family: "Roboto", Arial, sans-serif;
        font-size: 0.84rem;
        font-weight: 700;
        line-height: 1.3;
    }

    #home-intro .home-intro__showcase-header > span {
        padding: 3px 7px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        background: #242424;
        color: #bbb;
        font-size: 0.62rem;
        line-height: 1.2;
    }

    #home-intro .home-intro__controls {
        display: flex;
        gap: 4px;
        margin-left: auto;
    }

    #home-intro .home-intro__controls button {
        width: 29px;
        height: 27px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border: 1px solid rgba(255, 255, 255, 0.11);
        border-radius: 5px;
        background: #252525;
        color: #eee;
        font-size: 1.3rem;
        line-height: 1;
        cursor: pointer;
    }

    #home-intro .home-intro__controls button:hover {
        border-color: var(--home-hero-accent);
        color: var(--home-hero-accent);
    }

    #home-intro .home-intro__stage {
        position: relative;
        min-height: 310px;
        flex: 1 1 auto;
        overflow: hidden;
        background: #090909;
        aspect-ratio: 4 / 3;
    }

    #home-intro .home-intro__slide {
        position: absolute;
        inset: 0;
        display: block;
        overflow: hidden;
        color: #fff;
        opacity: 0;
        pointer-events: none;
        text-decoration: none;
        transition: opacity 0.45s ease;
    }

    #home-intro .home-intro__slide.is-active {
        z-index: 1;
        opacity: 1;
        pointer-events: auto;
    }

    #home-intro .home-intro__slide .home-intro__backdrop {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        filter: blur(12px) brightness(0.42);
        transform: scale(1.08);
    }

    #home-intro .home-intro__slide .home-intro__poster {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        display: block;
        padding: 12px 0 78px;
        object-fit: contain;
        object-position: center;
        filter: drop-shadow(0 8px 18px rgba(0, 0, 0, 0.5));
        transition: transform 5s ease;
    }

    #home-intro .home-intro__slide.is-active .home-intro__poster {
        transform: scale(1.035);
    }

    #home-intro .home-intro__slide:hover .home-intro__poster {
        transform: scale(1.07);
    }

    #home-intro .home-intro__poster-placeholder {
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, #292929, #111);
        color: #aaa;
        font-size: 0.9rem;
    }

    #home-intro .home-intro__image-shade {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.08) 25%, rgba(0, 0, 0, 0.9) 100%);
    }

    #home-intro .home-intro__film-info {
        position: absolute;
        right: 16px;
        bottom: 15px;
        left: 16px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.8);
    }

    #home-intro .home-intro__film-meta,
    #home-intro .home-intro__film-rating {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }

    #home-intro .home-intro__film-meta span {
        padding: 3px 7px;
        border-radius: 4px;
        background: var(--home-hero-accent);
        color: #111;
        font-size: 0.61rem;
        font-weight: 800;
        line-height: 1.25;
        text-shadow: none;
    }

    #home-intro .home-intro__film-meta span + span {
        background: rgba(0, 0, 0, 0.65);
        color: #eee;
    }

    #home-intro .home-intro__film-info strong {
        max-width: 100%;
        overflow: hidden;
        color: #fff;
        font-size: clamp(1.05rem, 1.7vw, 1.5rem);
        font-weight: 800;
        line-height: 1.2;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    #home-intro .home-intro__film-rating {
        color: #ddd;
        font-size: 0.72rem;
        line-height: 1.3;
    }

    #home-intro .home-intro__film-rating span:first-child {
        color: var(--home-hero-accent);
        font-weight: 700;
    }

    #home-intro .home-intro__thumbnails {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 7px;
        padding: 9px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        background: #151515;
    }

    #home-intro .home-intro__thumbnail {
        position: relative;
        min-width: 0;
        overflow: hidden;
        padding: 0;
        border: 2px solid transparent;
        border-radius: 4px;
        background: #252525;
        aspect-ratio: 16 / 9;
        cursor: pointer;
        opacity: 0.62;
        transition: border-color 0.2s ease, opacity 0.2s ease;
    }

    #home-intro .home-intro__thumbnail.is-active,
    #home-intro .home-intro__thumbnail:hover {
        border-color: var(--home-hero-accent);
        opacity: 1;
    }

    #home-intro .home-intro__thumbnail img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
    }

    #home-intro .home-intro__thumbnail span {
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        color: #ccc;
        font-size: 0.6rem;
    }

    #home-intro .home-intro__empty {
        min-height: 250px;
        display: grid;
        place-content: center;
        justify-items: center;
        gap: 8px;
        padding: 24px;
        color: #ccc;
        text-align: center;
    }

    #home-intro .home-intro__empty p {
        margin: 0;
        color: inherit;
        font-size: 0.9rem;
        animation: none;
    }

    #home-intro .home-intro__empty a {
        color: var(--home-hero-accent);
        font-size: 0.85rem;
        font-weight: 700;
    }

    #home-intro a:focus-visible,
    #home-intro button:focus-visible {
        outline: 2px solid #fff;
        outline-offset: 3px;
    }

    @media (max-width: 900px) {
        #home-intro {
            grid-template-columns: minmax(0, 1fr);
            gap: 22px;
        }

        #home-intro .home-intro__stage {
            min-height: 390px;
        }
    }

    @media (max-width: 560px) {
        #home-intro {
            min-height: 0;
            margin: 0 0 36px;
            padding: 18px;
            border-radius: 12px;
        }

        #home-intro .home-intro__title {
            font-size: clamp(1.85rem, 9vw, 2.6rem);
        }

        #home-intro .home-intro__stage {
            min-height: 330px;
        }

        #home-intro .home-intro__link {
            flex: 1 1 100%;
        }

        #home-intro .home-intro__thumbnail {
            aspect-ratio: 1 / 1;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        #home-intro .home-intro__link,
        #home-intro .home-intro__slide,
        #home-intro .home-intro__slide .home-intro__poster,
        #home-intro .home-intro__thumbnail {
            transition: none;
        }
    }
</style>

<script>
    (() => {
        const gallery = document.querySelector('#home-intro');
        if (!gallery) return;

        const slides = Array.from(gallery.querySelectorAll('[data-gallery-slide]'));
        const thumbnails = Array.from(gallery.querySelectorAll('[data-gallery-thumbnail]'));
        if (slides.length < 2) return;

        let activeIndex = 0;

        const showSlide = (index) => {
            activeIndex = (index + slides.length) % slides.length;

            slides.forEach((slide, slideIndex) => {
                const isActive = slideIndex === activeIndex;
                slide.classList.toggle('is-active', isActive);
                slide.setAttribute('aria-hidden', String(!isActive));
                slide.inert = !isActive;
            });

            thumbnails.forEach((thumbnail, thumbnailIndex) => {
                const isActive = thumbnailIndex === activeIndex;
                thumbnail.classList.toggle('is-active', isActive);
                thumbnail.setAttribute('aria-pressed', String(isActive));
            });
        };

        thumbnails.forEach((thumbnail) => {
            thumbnail.addEventListener('click', () => {
                showSlide(Number(thumbnail.dataset.galleryThumbnail));
            });
        });

        gallery.querySelectorAll('[data-gallery-direction]').forEach((button) => {
            button.addEventListener('click', () => {
                showSlide(activeIndex + Number(button.dataset.galleryDirection));
            });
        });

        showSlide(0);

        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            window.setInterval(() => {
                showSlide(activeIndex + 1);
            }, 10000);
        }
    })();
</script>