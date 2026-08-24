<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InscriptionUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/inscription');

        $email = 'julie+' . uniqid() . '@email.com';

        $client->submitForm('S\'inscrire', [
            'inscription_user[email]' => $email,
            'inscription_user[plainPassword][first]' => 'julie1234',
            'inscription_user[plainPassword][second]' => 'julie1234',
            'inscription_user[firstname]' => 'Julie',
            'inscription_user[lastname]' => 'Doe',
        ]);

        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        $this->assertSelectorTextContains('body', 'Votre compte a bien été créé');
    }
}