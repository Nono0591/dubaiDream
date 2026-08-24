<?php
// tests/Entity/ProductTest.php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testPriceWTStandard(): void
    {
        $product = new Product();
        $product->setPrice(100);
        $product->setTva(20);

        $this->assertSame(120.0, $product->getPriceWT());
    }

    public function testPriceWTReducedTva(): void
    {
        $product = new Product();
        $product->setPrice(50);
        $product->setTva(5.5);

        $this->assertSame(52.75, $product->getPriceWT());
    }

    public function testPriceWTZero(): void
    {
        $product = new Product();
        $product->setPrice(0);
        $product->setTva(20);

        $this->assertSame(0.0, $product->getPriceWT());
    }
}