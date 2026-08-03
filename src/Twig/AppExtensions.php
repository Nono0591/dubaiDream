<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\Extension\GlobalsInterface as GlobalInterface;
use App\Repository\CategoryRepository;
use App\Classe\Cart;

class AppExtensions extends AbstractExtension implements GlobalInterface
{

    private $categoryRepository;
    private $cart;

    public function __construct( CategoryRepository $categoryRepository, Cart $cart)
    {
        $this->categoryRepository = $categoryRepository;
        $this->cart = $cart;
    }

    public function getFilters(): array
    {
        return [   
            new TwigFilter('price', [ $this, 'formatPrice'])

        ];
    }

    public function formatPrice( $number)
    {
        return number_format($number, '2',',').'€';
    }

    public function getGlobals(): array
    {
        return[
            'allCategories' => $this->categoryRepository->findAll(),
            'fullCartquantity' => $this->cart->fullQuantity()

        ];
    }


}