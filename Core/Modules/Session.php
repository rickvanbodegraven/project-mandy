<?php

namespace Core\Modules;

/**
 * Session handling for the framework
 *
 * @package Core
 */
class Session extends Module
{
    private $session;

    // TODO perhaps implement a internal buffer session and flush that to $_SESSION on object destruction

    /**
     * Is called by the base Module class directly after the constructor has been called
     */
    protected function postCreation()
    {
        // set the name for the session cookie. MAKE SURE IT CONTAINS AT LEAST ONE LETTER.
        session_name(md5('APPLICATIONSESSIONKEY') . "COOKIE");
        session_start();

        $this->session =& $_SESSION;
    }

    /**
     * @param mixed $key
     * @param mixed $data
     *
     * @throws \Exception
     */
    public function set($key, $data)
    {
        if (is_string($key) !== true) {
            throw new \Exception("Session data can only be set in a string-based key");
        }

        $this->session[$key] = $data;
    }

    /**
     * @param bool|true $regenerate
     */
    public function destroy($regenerate = true)
    {
        session_destroy();

        if ($regenerate === true) {
            session_regenerate_id(true);
        }
    }

    /**
     * @param string $key
     *
     * @return mixed|bool
     */
    public function get($key)
    {
        if (isset($this->session[$key]) === false) {
            return false;
        }

        return $this->session[$key];
    }
}
