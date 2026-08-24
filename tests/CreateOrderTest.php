<?php

namespace App\Tests;

use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OrderTest extends WebTestCase
{
    public function testCreateOrder(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        // Crée un utilisateur de test avec une adresse
        $user = new User();
        $user->setEmail('order+' . uniqid() . '@email.com');
        $user->setFirstname('Client');
        $user->setLastname('Test');
        $user->setPassword($passwordHasher->hashPassword($user, 'password'));
        $em->persist($user);

        $address = new Address();
        $address->setFirstname('Client');
        $address->setLastname('Test');
        $address->setAddress('1 rue du Test');
        $address->setPostcode('59000');
        $address->setCity('Lille');
        $address->setCountry('FR');
        $address->setPhone('0600000000');
        $address->setUser($user);
        $em->persist($address);

        // Crée un transporteur de test
        $carrier = new Carrier();
        $carrier->setName('Transporteur test');
        $carrier->setDescription('Livraison test');
        $carrier->setPrice(4.90);
        $em->persist($carrier);

        // Crée un produit de test
        $product = new Product();
        $product->setName('Parfum de test');
        $product->setSlug('parfum-test-' . uniqid());
        $product->setDescription('Produit créé pour le test');
        $product->setIllustration('test.jpg');
        $product->setPrice(29.90);
        $product->setTva(20);
        $em->persist($product);

        $em->flush();

        $client->loginUser($user);

        // Ajoute le produit au panier
        $client->request('GET', '/cart/add/' . $product->getId());

        // Accède au formulaire de livraison
        $crawler = $client->request('GET', '/commande/livraison');
        $this->assertResponseIsSuccessful();

        // Soumet le formulaire avec l'adresse et le transporteur créés
        $client->submitForm('Valider', [
            'order[addresses]' => (string) $address->getId(),
            'order[carriers]' => (string) $carrier->getId(),
        ]);

        // La commande est créée, redirection vers la page de confirmation
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('body', 'confirm');
    }
}