<?php

namespace Core\Modules;

/**
 * Class Security
 *
 * @package Core
 */
class Security extends Module
{
    private $requestIsEncrypted = false;
    private $domain = "";

    public function __construct()
    {
        if (isset($_SERVER['HTTPS']) === true) {
            $this->requestIsEncrypted = true;
        }

        $this->domain = $_SERVER['SERVER_NAME'];

        $this->setHeaders();
        $this->configureSettings();
    }

    /**
     *
     */
    private function setHeaders()
    {
        // most strict version of anti-clickjacking
        header('X-Frame-Options: deny');

        // prevent content type sniffing by the browser (anti-stegosploit)
        header('X-Content-Type-Options: nosniff');

        // tell IE to disallow XSS execution (not very good but every bit helps)
        header('X-XSS-Protection: 1; mode=block');

        // the CSP-header in a very strict fashion
        header("Content-Security-Policy: default-src 'self'");

        // remove the PHP version header too
        header_remove('x-powered-by');
    }

    /**
     *
     */
    private function configureSettings()
    {
        session_set_cookie_params(0, "/", $this->domain, false, true);

        if ($this->requestIsEncrypted === true) {
            session_set_cookie_params(0, "/", $this->domain, true, true);
        }

        // TODO perhaps set some more sec. settings?
    }
}
