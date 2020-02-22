<?php


namespace App\Events;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class InvoiceChronoSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;
    private $repository;

    /**
     * InvoiceChronoSubsciber constructor.
     * @param Security $security
     * @param $repository
     */
    public function __construct(Security $security, InvoiceRepository $repository)
    {
        $this->security = $security;
        $this->repository = $repository;
    }


    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setChronoForInvoice', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setChronoForInvoice(ViewEvent $event){

        $invoice= $event->getControllerResult();

        $method = $event ->getRequest()->getMethod();

        if($invoice instanceof Invoice && $method === "POST" ){
            //Trouver l'utilisateur connecté (Sécurity), le Repository des factures afin de donner un chrono
            $invoice->setChrono($this->repository->findChronoPlus($this->security->getUser()));

            if(empty($invoice->getSentAt())){
                $invoice->setSentAt(new \DateTime());
            }
        }

    }
}