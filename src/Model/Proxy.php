<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 18/10/19
 * Time: 15:46
 */

namespace App\Model;


class Proxy
{

    private $host;
    private $port;
    private $secure = false;
    private $login;
    private $password;
    private $down;
    private $blacklisted;

    public function __construct( $host, $port = null, $secure = false, $login = null, $password = null ) {
        $this->host = $host;
        $this->port = $port;
        $this->secure = $secure;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @return null
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $http
     */
    public function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @param null $port
     */
    public function setPort($port): void
    {
        $this->port = $port;
    }

    /**
     * @param bool $secure
     */
    public function setSecure(bool $secure): void
    {
        $this->secure = $secure;
    }

    /**
     * @param null $login
     */
    public function setLogin($login): void
    {
        $this->login = $login;
    }

    /**
     * @param null $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function isDown() {
        return $this->down;
    }

    public function setDown() {
        $this->down = true;
    }

    public function setUp() {
        $this->down = false;
    }

    public function setBlacklisted() {
        $this->blacklisted = true;
    }

    public function setWhitelisted() {
        $this->blacklisted = false;
    }

    public function isBlacklisted() {
        return $this->blacklisted;
    }
}
