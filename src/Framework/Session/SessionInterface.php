<?php


namespace App\Framework\Session;

interface SessionInterface
{

    /***
     * Get an information in the session
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Add an information in the session
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function set(string $key, $value): void;

    /**
     * Remove an information in the session
     * @param string $key
     * @return mixed
     */
    public function delete(string $key) :void;
}
