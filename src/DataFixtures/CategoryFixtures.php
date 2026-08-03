<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Parfums',
            'Beauté',
            'Maison',
            'Hygiène',
        ];

        foreach ($categories as $index => $name) {
            $category = new Category();

            $category->setName($name);
            $category->setSlug(strtolower($this->slugger->slug($name)));

            $manager->persist($category);

            $this->addReference('category_' . $index, $category);
        }

        $manager->flush();
    }
}
