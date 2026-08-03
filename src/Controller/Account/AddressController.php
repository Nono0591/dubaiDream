<?php
namespace App\Controller\Account;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class AddressController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function index(): Response
    {
        return $this->render('account/address/index.html.twig');
    }

    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_address_add', defaults:['id' =>null] )]
    public function add(Request $request, $id, AddressRepository $addressRepository, Cart $cart): Response
    {
        if($id){
            $address = $addressRepository ->findById($id);
            if(!$address || $address->getUser() != $this->getUser()) {
                return $this->redirectToRoute('app_account_addresses');
            }
        }

        $address = new Address();
        $address->setUser($this->getUser());

        $form = $this->createForm(AddressUserType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre adresse a bien été ajoutée');

            if($cart->fullQuantity() > 0 ) {
                return $this->redirectToRoute('app_order');
            }

            return $this->redirectToRoute('app_account_addresses');
        }

        return $this->render('account/address/form.html.twig', [
            'addressForm' => $form->createView(),
        ]);
    }

    #[Route('/compte/adresse/modifier/{id}', name: 'app_account_address_edit')]
    public function edit(Request $request, int $id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findById($id);

        if (!$address || $address->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_account_addresses');
        }

        $form = $this->createForm(AddressUserType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre adresse a bien été modifiée');
            return $this->redirectToRoute('app_account_addresses');
        }

        return $this->render('account/address/form.html.twig', [
            'addressForm' => $form->createView(),
        ]);
    }

    #[Route('/compte/adresse/supprimer/{id}', name: 'app_account_address_delete')]
    public function delete(int $id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findById($id);

        if (!$address || $address->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_account_addresses');
        }

        $this->entityManager->remove($address);
        $this->entityManager->flush();
        $this->addFlash('success', 'Votre adresse a bien été supprimée');

        return $this->redirectToRoute('app_account_addresses');
    }
}