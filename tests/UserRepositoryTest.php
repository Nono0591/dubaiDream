<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class UserRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    // Le repository qu'on veut réellement tester
    private UserRepository $repository;

    protected function setUp(): void
    {
        // Démarre le kernel Symfony (charge la config, les services, etc.)
        self::bootKernel();

        // Récupère l'EntityManager depuis le conteneur de services Symfony
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        // Récupère le repository réel des Users depuis le conteneur
        $this->repository = static::getContainer()->get(UserRepository::class);
    }

    /**
     * Vérifie qu'un utilisateur qu'on vient de créer et sauvegarder en base
     * peut bien être retrouvé via son email.
     */
    public function testFindByEmailReturnsUser(): void
    {
        // uniqid() évite d'avoir deux fois le même email si le test tourne plusieurs fois
        $email = 'integration+' . uniqid() . '@email.com';

        // Création d'un vrai objet User avec des données de test
        $user = new User();
        $user->setEmail($email);
        $user->setFirstname('Test');
        $user->setLastname('Integration');
        $user->setPassword('fakepassword'); // mot de passe factice, pas hashé ici

        // persist() prépare l'entité à être enregistrée
        $this->em->persist($user);
        // flush() exécute réellement la requête SQL INSERT en base
        $this->em->flush();

        // On interroge la vraie base pour retrouver l'utilisateur par son email
        $found = $this->repository->findOneBy(['email' => $email]);

        // Vérifie qu'on a bien trouvé quelque chose (pas null)
        $this->assertNotNull($found);
        // Vérifie que l'email de l'utilisateur trouvé correspond bien
        $this->assertSame($email, $found->getEmail());
    }

    /**
     * Vérifie que la recherche renvoie bien null
     * quand aucun utilisateur ne correspond à l'email donné.
     */
    public function testFindByEmailReturnsNullWhenNotFound(): void
    {
        // Email qui n'existe volontairement pas en base
        $found = $this->repository->findOneBy(['email' => 'inexistant@email.com']);

        // On s'attend à ce que le résultat soit null
        $this->assertNull($found);
    }
}