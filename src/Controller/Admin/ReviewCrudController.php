<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom du client'),
            IntegerField::new('reviewsCount', 'Nombre d avis Google'),
            IntegerField::new('rating', 'Note'),
            TextareaField::new('text', 'Commentaire'),
            TextField::new('date', 'Date de publication')
                ->setHelp('Exemple : il y a 4 mois'),
            TextField::new('visitDate', 'Date de visite')
                ->setHelp('Exemple : Visité en mars'),
            TextField::new('avatar', 'Initiales')
                ->setHelp('Exemple : SM'),
            BooleanField::new('isVisible', 'Afficher sur le site'),
            IntegerField::new('position', 'Ordre d affichage'),
        ];
    }
}