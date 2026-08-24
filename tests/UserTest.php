<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetRolesAlwaysIncludesRoleUser(): void
    {
        $user = new User();

        // Un utilisateur sans rôle explicite a quand même ROLE_USER
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testGetRolesReturnsUniqueValues(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']); // ROLE_USER déjà présent en double

        $roles = $user->getRoles();

        // Pas de doublon malgré l'ajout automatique de ROLE_USER
        $this->assertCount(2, $roles);
        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles);
    }
}