<?php

namespace Core;

/**
 * Class Security
 *
 * @package Core
 */
class Security
{
    public function __construct()
    {
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
    }

    private function configureSettings()
    {
        // TODO --- implement secure/httponly cookies etc
    }
}