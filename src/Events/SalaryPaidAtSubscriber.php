<?php


namespace App\Events;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Salary;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class SalaryPaidAtSubscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => ['setPaidAtForSalary', EventPriorities::PRE_VALIDATE]
        ];
    }
    public function setPaidAtForSalary(ViewEvent $event){

        $salary = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($salary instanceof Salary && $method === "POST"){
            if(empty($salary->getPaidAt())){
                $salary->setPaidAt(new \DateTime());
            }
        }

    }
}