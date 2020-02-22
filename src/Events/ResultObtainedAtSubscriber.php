<?php


namespace App\Events;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Result;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class ResultObtainedAtSubscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => ['setObtainedAtForResult', EventPriorities::PRE_VALIDATE]
        ];
    }
    public function setObtainedAtForResult(ViewEvent $event){

        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($result instanceof Result){
            if(empty($result->getObtainedAt())){
                $result->setObtainedAt(new \DateTime());
            }
        }

    }
}