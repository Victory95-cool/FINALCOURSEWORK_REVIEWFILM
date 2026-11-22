<?php

class Watchlist
{
    public function contains($pdo, $reviewerId, $filmId)
    {
        return (bool) Database::query(
            $pdo,
            'SELECT EXISTS(
                SELECT 1 FROM watchlist WHERE reviewer_id = :reviewer_id AND film_id = :film_id
            )',
            [':reviewer_id' => $reviewerId, ':film_id' => $filmId]
        )->fetchColumn();
    }

    public function allForReviewer($pdo, $reviewerId)
    {
        return Database::query(
            $pdo,
            'SELECT f.id, f.title, f.released_year, f.poster, w.added_at
             FROM watchlist w
             INNER JOIN film f ON f.id = w.film_id
             WHERE w.reviewer_id = :reviewer_id
             ORDER BY w.added_at DESC',
            [':reviewer_id' => $reviewerId]
        )->fetchAll();
    }

    public function add($pdo, $reviewerId, $filmId)
    {
        Database::query(
            $pdo,
            'INSERT IGNORE INTO watchlist (reviewer_id, film_id) VALUES (:reviewer_id, :film_id)',
            [':reviewer_id' => $reviewerId, ':film_id' => $filmId]
        );
    }

    public function remove($pdo, $reviewerId, $filmId)
    {
        Database::query(
            $pdo,
            'DELETE FROM watchlist WHERE reviewer_id = :reviewer_id AND film_id = :film_id',
            [':reviewer_id' => $reviewerId, ':film_id' => $filmId]
        );
    }
}
