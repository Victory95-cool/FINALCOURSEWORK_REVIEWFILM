<?php

class ReviewerAuth
{
    public function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function currentReviewerId()
    {
        $this->startSession();
        return isset($_SESSION['reviewer_id']) ? (int) $_SESSION['reviewer_id'] : null;
    }

    public function requireLogin()
    {
        if (!$this->currentReviewerId()) {
            header('Location: Login/signin.php');
            exit();
        }
    }

    public function signIn($reviewer)
    {
        $this->startSession();
        session_regenerate_id(true);
        $_SESSION['reviewer_id'] = (int) $reviewer['id'];
        $_SESSION['user_registered_name'] = $reviewer['username'];
        $_SESSION['user_id'] = (int) $reviewer['id'];
        $_SESSION['user_name'] = $reviewer['username'];
    }

    public function signOut()
    {
        $this->startSession();
        unset(
            $_SESSION['reviewer_id'],
            $_SESSION['user_registered_name'],
            $_SESSION['user_id'],
            $_SESSION['user_name']
        );
        session_regenerate_id(true);
    }
}
