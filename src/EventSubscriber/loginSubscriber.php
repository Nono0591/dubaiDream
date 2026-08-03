<?php

namespace App\EventSubscriber;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;


class loginSubscriber implements EventSubscriberInterface
{   
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function onLogin()
    {

        $user = $this->security->getUser();
        $user->setLastLoginAt(new DateTime());
        $this->entityManager->flush();
    }
    public static function getSubscribedEvents(): array
    {
        return [
           LoginSuccessEvent::class => 'onLogin',
        ];
    }
}