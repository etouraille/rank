<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SentenceRepository")
 */
class Sentence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=600)
     */
    private $value;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $length;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="integer")
     */
    private $articleRank;

    /**
     * @ORM\Column(type="integer")
     */
    private $used=0;

    /**
     * @ORM\Column(type="integer")
     */
    private $count=0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId( $id ) {
        $this->id = $id;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getLength(): ?int
    {
        preg_match_all('/(( )|(,)|(;))/', $this->value, $match);
        $NSeparator = count($match[0]);
        return !isset($this->value)?0:$NSeparator + 1;


    }

    public function setLength(?int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getArticleRank(): ?int
    {
        return $this->articleRank;
    }

    public function setArticleRank(int $articleRank): self
    {
        $this->articleRank = $articleRank;

        return $this;
    }

    public function getUsed(): ?int
    {
        return $this->used;
    }

    public function setUsed(int $used): self
    {
        $this->used = $used;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getType() {
        return 'neutre';
    }
}
