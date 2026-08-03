<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InscriptionUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        //1. Créer un faux client HTTP et pointer vers l'url de l'inscription
        $client = static::createClient();
        $client->request('GET', '/inscription');

        //2 le nom des champs du formulaire(name dans l'inspecteur)
        $client->submitForm('S\'inscrire', [
            'inscription_user[email]' => 'julie@email.com',
            'inscription_user[plainPassword][first]' => 'julie1234',
            'inscription_user[plainPassword][second]' => 'julie1234',
            'inscription_user[firstname]' => 'Julie',
            'inscription_user[lastname]' => 'Doe',
        ]);

        //follow
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        //3 verifier le message de succes avec l'alerte
        $this->assertSelectorExists('div.alert.alert-success','Votre compte a bien été créé');


    }
}

