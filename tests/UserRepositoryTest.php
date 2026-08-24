<?php
// tests/Repository/UserRepositoryTest.php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private UserRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        $this->repository = static::getContainer()->get(UserRepository::class);
    }

    public function testFindByEmailReturnsUser(): void
    {
        $email = 'integration+' . uniqid() . '@email.com';

        $user = new User();
        $user->setEmail($email);
        $user->setFirstname('Test');
        $user->setLastname('Integration');
        $user->setPassword('fakepassword');

        $this->em->persist($user);
        $this->em->flush();

        $found = $this->repository->findOneBy(['email' => $email]);

        $this->assertNotNull($found);
        $this->assertSame($email, $found->getEmail());
    }

    public function testFindByEmailReturnsNullWhenNotFound(): void
    {
        $found = $this->repository->findOneBy(['email' => 'inexistant@email.com']);

        $this->assertNull($found);
    }
}