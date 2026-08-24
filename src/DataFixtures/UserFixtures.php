<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Création de l'admin
        $admin = new User();
        $admin->setEmail('admin@dubaidream59.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('Dubai Dream');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->hasher->hashPassword($admin, 'admin1234')
        );
        $manager->persist($admin);
        $this->addReference('admin_user', $admin);

        // Création de 10 utilisateurs fictifs
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setPassword(
                $this->hasher->hashPassword($user, 'password')
            );
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}