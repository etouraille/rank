<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProxyRepository")
 */
class Proxy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $host;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $port;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $secure;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $down;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $blacklisted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(?int $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getSecure(): ?bool
    {
        return $this->secure;
    }

    public function setSecure(?bool $secure): self
    {
        $this->secure = $secure;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDown(): ?bool
    {
        return $this->down;
    }

    public function setDown(?bool $down): self
    {
        $this->down = $down;

        return $this;
    }

    public function getBlacklisted(): ?bool
    {
        return $this->blacklisted;
    }

    public function setBlacklisted(?bool $blacklisted): self
    {
        $this->blacklisted = $blacklisted;

        return $this;
    }
}
