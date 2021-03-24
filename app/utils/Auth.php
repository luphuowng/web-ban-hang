<?php


namespace App\Utils;


use Core\App;

class Auth
{
    /**
     * Check Authenticated: Checks to see if the user is authenticated,
     * destroying the session and redirecting to a specific location if the user
     * session doesn't exist.
     * @access public
     * @param string $name
     * @throws \Exception
     * @since 1.0.2
     */
    public static function checkAuthenticated($name = "login")
    {

        if (!isset($_SESSION['user_id'])) {
            session_destroy();
            redirect($name);
        }
    }

    /**
     * Check Unauthenticated: Checks to see if the user is unauthenticated,
     * redirecting to a specific location if the user session exist.
     * @access public
     * @param string $redirect
     * @throws \Exception
     * @since 1.0.2
     */
    public static function checkUnauthenticated($redirect = "")
    {
        Session::init();
        if (isset($_SESSION['user_id'])) {
            redirect($redirect);
        }
    }
}