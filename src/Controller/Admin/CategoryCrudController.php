<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;


class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

       public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories')
          ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Titre')->setHelp('Titre de la catégorie'),
            SlugField::new('slug')->setLabel('URL')->setTargetFieldName('name')->setHelp('Généré automatiquement à partir du titre'),
            ImageField::new('illustration')
            ->setLabel('Image du produit')
            ->setHelp('Image de votre produit en 600*600px')
            ->setBasePath('/uploads')
            ->setUploadDir('public/uploads')
            ->setRequired(true),
            TextField::new('description')
            ->setLabel('Description')
            ->setHelp('Courte description de la catégorie')
        ];
    }
    

}
