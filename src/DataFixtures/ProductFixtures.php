<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $products = [
            ['T-shirt Classic', 'tshirt.jpg', 19.99, 0],
            ['Jean Slim', 'jean.jpg', 49.99, 0],
            ['Sneakers Urban', 'sneakers.jpg', 79.99, 1],
            ['Veste Cuir', 'veste.jpg', 149.99, 1],
            ['Casquette Logo', 'casquette.jpg', 24.99, 2],
            ['Hoodie Oversize', 'hoodie.jpg', 59.99, 2],
        ];

        foreach ($products as $index => [$name, $image, $price, $catIndex]) {
            $product = new Product();

            $product->setName($name);
            $product->setSlug(strtolower($this->slugger->slug($name)));
            $product->setDescription("Description de $name");
            $product->setIllustration($image);
            $product->setPrice($price);
            $product->setTva(20);

            $product->setCategory(
                $this->getReference('category_' . $catIndex, Category::class)
            );

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}