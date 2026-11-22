<?php

class Csrf
{
    public function token()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public function assertValid($token)
    {
        if (!$this->isValid($token)) {
            http_response_code(403);
            exit('Invalid request token. Reload the page and try again.');
        }
    }

    public function isValid($token)
    {
        $sessionToken = $this->token();
        return is_string($token) && hash_equals($sessionToken, $token);
    }
}
