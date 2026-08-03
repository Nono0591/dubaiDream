<?php

namespace App\DataFixtures;

use App\Entity\Carrier;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $states = [1, 2, 3, 4];
        $carriers = [
            ['name' => 'Colissimo', 'price' => 4.99],
            ['name' => 'Chronopost', 'price' => 9.99],
            ['name' => 'DHL', 'price' => 12.99],
        ];
        $products = [
            ['name' => 'T-shirt Bleu', 'illustration' => 'tshirt-bleu.jpg', 'price' => 19.99, 'tva' => 20],
            ['name' => 'Jean Slim', 'illustration' => 'jean-slim.jpg', 'price' => 49.99, 'tva' => 20],
            ['name' => 'Veste Légère', 'illustration' => 'veste.jpg', 'price' => 79.99, 'tva' => 20],
            ['name' => 'Chemise Blanche', 'illustration' => 'chemise.jpg', 'price' => 34.99, 'tva' => 20],
            ['name' => 'Pull Laine', 'illustration' => 'pull.jpg', 'price' => 59.99, 'tva' => 20],
        ];

        for ($i = 1; $i <= 10; $i++) {
            $user = $this->getReference('user_' . $i, User::class);
            $carrier = $carriers[array_rand($carriers)];

            $address = $user->getFirstname() . ' ' . $user->getLastname() . '<br/>';
            $address .= '12 rue de la Paix<br/>';
            $address .= '75001 Paris<br/>';
            $address .= 'France<br/>';
            $address .= '0601020304';

            $order = new Order();
            $order->setUser($user);
            $order->setCreatedAt(new \DateTime('-' . rand(1, 30) . ' days'));
            $order->setState($states[array_rand($states)]);
            $order->setCarrierName($carrier['name']);
            $order->setCarrierPrice($carrier['price']);
            $order->setDelivery($address);

            // 1 à 3 produits par commande
            $nbProducts = rand(1, 3);
            $selectedProducts = array_rand($products, $nbProducts);
            if (!is_array($selectedProducts)) {
                $selectedProducts = [$selectedProducts];
            }

            foreach ($selectedProducts as $productIndex) {
                $product = $products[$productIndex];
                $orderDetail = new OrderDetail();
                $orderDetail->setProductName($product['name']);
                $orderDetail->setProductIllustration($product['illustration']);
                $orderDetail->setProductPrice($product['price']);
                $orderDetail->setProductTva($product['tva']);
                $orderDetail->setProductQuantity((string) rand(1, 3));
                $order->addOrderDetail($orderDetail);
            }

            $manager->persist($order);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}