<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PublicationRepository")
 */
class Publication
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $word;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="object", nullable=true)
     */
    private $target;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWord(): ?int
    {
        return $this->word;
    }

    public function setWord(int $word): self
    {
        $this->word = $word;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($target): self
    {
        $this->target = $target;

        return $this;
    }
}
