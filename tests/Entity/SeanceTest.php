<?php

namespace App\Tests\Entity;

use App\Entity\Seance;
use App\Entity\User;
use App\Entity\Programme;
use PHPUnit\Framework\TestCase;

class SeanceTest extends TestCase
{
    public function testDate()
    {
        $seance = new Seance();
        $date = new \DateTime('2025-07-07');
        $seance->setDate($date);

        $this->assertSame($date, $seance->getDate());
    }

    public function testUser()
    {
        $seance = new Seance();
        $user = new User();
        $seance->setUser($user);

        $this->assertSame($user, $seance->getUser());
    }

    public function testProgramme()
    {
        $seance = new Seance();
        $programme = new Programme();
        $seance->setProgramme($programme);

        $this->assertSame($programme, $seance->getProgramme());
    }
}
