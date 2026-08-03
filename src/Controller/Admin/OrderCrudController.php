<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Classe\State;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Request;



class OrderCrudController extends AbstractCrudController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManager = $entityManagerInterface;
    }           

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
        ;
    }
    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('showOrder', 'Afficher')->linkToCrudAction('showOrder');
        return $actions
            ->add(Crud::PAGE_INDEX, $show)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

    /*
    * Fonction pour changer le statut de la commande
    */

    // 1 Modifier le statut de la commande dans la base de données
    public function changeState($order, $state){

        $order->setState($state);
        $this->entityManager->flush();
    
    // 2 Ajouter un message flash pour informer l'administrateur du changement de statut

        $this->addFlash('success', 'Le statut de la commande a été mis à jour.');
  
    
    // 3 Informer l'utilisateur par mail de l'évolution de sa commande (optionnel)
        $mail = new Mail();
        $vars = [
            'firstname' => $order->getUser()->getFirstname(),
            'id_order' => $order->getId(),
        ];
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname().' '. $order->getUser()->getLastname(), State::STATE[$state]['email_subject'], State::STATE[$state]['email_template'], $vars);
    }


    public function showOrder(AdminContext $context, AdminUrlGenerator $adminUrlGenerator,Request $request)
    {

        // Récupérer la commande à partir du contexte
        try {
            $order = $context->getEntity()->getInstance();
        } catch (\LogicException $e) {
            return $this->redirectToRoute('admin', [
                'crudControllerFqcn' => self::class,
                'crudAction' => 'index',
            ]);
        }

        if (!$order instanceof Order) {
            throw $this->createNotFoundException('Commande introuvable.');
        }

        $url = $adminUrlGenerator
            ->setController(self::class)
            ->setAction('showOrder')
            ->setEntityId($order->getId())
            ->generateUrl();
        
              
        // traitemeent des changements de statut

        if($request->get('state')){
            $this->changeState($order, $request->get('state'));

        }


        return $this->render('admin/order.html.twig', [
            'order' => $order,
            'current_url' => $url,
        ]);
    }


    public function configureFields(string $pageName): iterable
    {
        $stateField = NumberField::new('state')->setLabel('Statut');

        if ($pageName !== Crud::PAGE_INDEX) {
            $stateField->setTemplatePath('admin/state.html.twig');
        }

        return [
            IdField::new('id'),
            DateField::new('createdAt')->setLabel('Date de création'),
            $stateField,
            AssociationField::new('user')->setLabel('Utilisateur'),
            TextField::new('carrierName')->setLabel('Transporteur'),
            NumberField::new('totalTva')->setLabel('Total TVA'),
            NumberField::new('totalWt')->setLabel('Total TTC'),
        ];
    }
}