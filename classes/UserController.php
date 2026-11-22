<?php

class UserController
{
    private $reviewers;
    private $reviewerAuth;

    public function __construct(Reviewer $reviewers, ReviewerAuth $reviewerAuth)
    {
        $this->reviewers = $reviewers;
        $this->reviewerAuth = $reviewerAuth;
    }

    public function signIn($pdo, $identifier, $password)
    {
        if (!is_string($identifier) || !is_string($password)) {
            return false;
        }

        $reviewer = $this->reviewers->findByLogin($pdo, trim($identifier));
        if (!$reviewer || empty($reviewer['password_hash']) || !password_verify($password, $reviewer['password_hash'])) {
            return false;
        }

        if (!empty($reviewer['is_banned'])) {
            return [
                'banned' => true,
                'message' => (string) ($reviewer['ban_message'] ?? ''),
            ];
        }

        $this->reviewerAuth->signIn($reviewer);
        return true;
    }

    public function signUp($pdo, $username, $email, $phone, $password, $passwordConfirmation)
    {
        $username = is_string($username) ? trim($username) : '';
        $email = is_string($email) ? trim($email) : '';
        $phone = is_string($phone) ? trim($phone) : '';

        if ($username === '' || strlen($username) > 100) {
            throw new InvalidArgumentException('Username is required and must be 100 characters or fewer.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with(strtolower($email), '@gmail.com') && !str_ends_with(strtolower($email), '@gre.ac.uk')) {
            throw new InvalidArgumentException('Only @gmail.com or @gre.ac.uk addresses are accepted.');
        }
        if (!preg_match('/^\d{10}$/', $phone)) {
            throw new InvalidArgumentException('Phone number must contain exactly 10 digits.');
        }
        if (!is_string($password) || strlen($password) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters long.');
        }
        if (!is_string($passwordConfirmation) || $password !== $passwordConfirmation) {
            throw new InvalidArgumentException('Passwords do not match.');
        }
        if ($this->reviewers->emailExists($pdo, $email)) {
            throw new InvalidArgumentException('That email address is already registered.');
        }
        if ($this->reviewers->usernameExists($pdo, $username)) {
            throw new InvalidArgumentException('That username is already taken.');
        }

        $reviewerId = $this->reviewers->insertReviewer(
            $pdo,
            $username,
            $phone,
            $email,
            password_hash($password, PASSWORD_DEFAULT)
        );
        $this->reviewerAuth->signIn(['id' => $reviewerId, 'username' => $username]);
        return $reviewerId;
    }

    public function signOut()
    {
        $this->reviewerAuth->signOut();
    }

    public function currentUserId()
    {
        return $this->reviewerAuth->currentReviewerId();
    }

    public function requireLogin()
    {
        $this->reviewerAuth->requireLogin();
    }
}
