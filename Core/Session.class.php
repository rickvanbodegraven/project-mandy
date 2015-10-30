<?php

namespace Core;

/**
 * Session handling for the framework
 *
 * @package Core
 */
class Session
{
    private $session;

    public function __construct()
    {
        $this->initialize();

        $this->session =& $_SESSION;
    }

    private function initialize()
    {
        session_start();
    }

    public function set($key, $data)
    {

    }

    public function destroy($regenerate = true)
    {
        session_destroy();

        if ($regenerate) {
            session_regenerate_id(true);
        }
    }

    /**
     * @param string $key
     * @return mixed|bool
     */
    public function get($key)
    {
        if (isset($this->session[$key]) === false)
            return false;

        return $this->session[$key];
    }
}