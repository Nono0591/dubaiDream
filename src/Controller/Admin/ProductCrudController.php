<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

     public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
          ;
    }

    public function configureFields(string $pageName): iterable
    {
        $required = true;

        if ($pageName === 'edit') {
            $required = false; 
        }

        return [
            TextField::new('name')->setLabel('Nom du produit'),
            BooleanField::new('isHomepage')->setLabel('Produit en avant ?')->setHelp('Cochez cette case pour mettre en avant ce produit sur la page d\'accueil'),
            SlugField::new('slug')->setLabel('URL')->setTargetFieldName('name')->setHelp('Généré automatiquement à partir du nom du produit'),
            TextEditorField::new('description') ->setLabel('Description du produit')->setHelp('Description complète du produit'),
            ImageField::new('illustration')
            ->setLabel('Image du produit')
            ->setHelp('Image de votre produit en 600*600px')
            ->setBasePath('/uploads/products')
            ->setUploadDir('public/uploads/products')
            ->setRequired($required),
            NumberField::new('price')->setLabel('Prix HT')->setHelp('Prix hors taxe du produit en euros'),
            ChoiceField::new('tva')->setLabel('TVA')->setHelp('Taux de TVA applicable au produit')->setChoices([
                '20%' => '20',
                '10%' => '10',
                '5.5%' => '5.5',
                '2.1%' => '2.1',
            ]),
            AssociationField::new('category')->setLabel('Catégorie')->setHelp('Catégorie à laquelle le produit appartient'),
        ];
    }
    
}
