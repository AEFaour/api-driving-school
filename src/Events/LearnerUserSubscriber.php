<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Learner;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class LearnerUserSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;
    /**
     * LearnerUserSubscriber constructor.
     * @param $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUserForLearner', EventPriorities::PRE_VALIDATE]
        ];
    }
    public function setUserForLearner(ViewEvent $event){
        $learner = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($learner instanceof Learner && $method === "POST"){
            //Trouver l'utilisateur connecté et lier le stagiaire à cet utilisateur
            $learner->setUser($this->security->getUser());
        }
    }
}