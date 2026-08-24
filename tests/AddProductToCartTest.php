<?php

namespace App\Tests;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartTest extends WebTestCase
{
    public function testAddProductToCart(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get(EntityManagerInterface::class);

        // Crée un produit dédié à ce test, isolé des données existantes
        $product = new Product();
        $product->setName('Parfum de test ' . uniqid());
        $product->setSlug('parfum-test-' . uniqid());
        $product->setDescription('Produit créé pour le test');
        $product->setIllustration('test.jpg');
        $product->setPrice(29.90);
        $product->setTva(20);

        $em->persist($product);
        $em->flush();

        $client->request('GET', '/cart/add/' . $product->getId());

        $this->assertResponseRedirects('/mon-panier');
        $client->followRedirect();

        $this->assertSelectorTextContains('.card-body', $product->getName());
    }
}