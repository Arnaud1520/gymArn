<?php

namespace App\Tests\Entity;

use App\Entity\Exercice;
use PHPUnit\Framework\TestCase;

class ExerciceTest extends TestCase
{
    public function testSetName()
    {
        $exercice = new Exercice();
        $exercice->setName('Pompes');
        $this->assertEquals('Pompes', $exercice->getName());
    }

    public function testSetCategorie()
    {
        $exercice = new Exercice();
        $exercice->setCategorie('Pectoraux');
        $this->assertEquals('Pectoraux', $exercice->getCategorie());
    }

    public function testSetDescription()
    {
        $exercice = new Exercice();
        $exercice->setDescription('Exercice de musculation pour les pectoraux');
        $this->assertEquals('Exercice de musculation pour les pectoraux', $exercice->getDescription());
    }
}
