<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */
class Phrase
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string",name="phrase",length=600)
     */
    protected $phrase;

    /**
     * @ORM\Column(type="integer",name="used")
     */
    protected $used=0;

    /**
     * @ORM\Column(type="integer",name="idLanguage")
     */
    protected $idLanguage=1;


    /**
     * @ORM\Column(type="integer",name="count")
     */
    protected $count=0;

    public function __construct() {

    }

    public function getId() {
        return $this->id;
    }

    public function setId( $id ) {
        $this->id = $id;
        return $this;
    }


    public function setPhrase( $phrase ) {
        $this->phrase = $phrase;
        return $this;
    }

    public function getPhrase() {
        return $this->phrase;
    }

    public function setUsed( $used ) {
        $this->used = $used;
        return $this;
    }

    public function getUsed () {
        return $this->used;
    }

    public function setIdLanguage( $idLanguage ) {
        $this->idLanguage = $idLanguage;
        return $this;
    }

    public function getIdLanguage () {
        return $this->idLanguage;
    }

    public function setCount( $count ) {
        $this->count = $count;
        return $this;
    }

    public function getCount () {
        return $this->count;
    }
}
