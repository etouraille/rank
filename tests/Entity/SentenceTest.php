<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 18/10/19
 * Time: 11:53
 */

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use App\Entity\Sentence;

class SentenceTest extends TestCase
{
    public function testLength() {

        $sentence = new Sentence();
        $sentence->setValue("La vie,des;mouettes;encore");
        $this->assertEquals(5, $sentence->getLength());
    }

    public function testOneWord() {
        $sentence = new Sentence();
        $sentence->setValue("Encore");
        $this->assertEquals(1, $sentence->getLength());
    }

    public function testEmptySentence() {
        $sentence = new Sentence();
        $this->assertEquals(0, $sentence->getLength());
    }
}
