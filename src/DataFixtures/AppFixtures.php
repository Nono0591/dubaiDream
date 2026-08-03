<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Aucun chargement ici
        // Les fixtures sont gérées dans :
        // - CategoryFixtures
        // - ProductFixtures
    }
}