<?php

class AdminAuth
{
    public function signIn($admin)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);
        $_SESSION['Authorised'] = 'Y';
        $_SESSION['admin_id'] = (int) $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
    }

    public function isAuthenticated()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['Authorised'], $_SESSION['admin_id'])
            && $_SESSION['Authorised'] === 'Y'
            && (int) $_SESSION['admin_id'] > 0;
    }

    public function signOut()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['Authorised'], $_SESSION['admin_id'], $_SESSION['admin_username']);
        session_regenerate_id(true);
    }
}
