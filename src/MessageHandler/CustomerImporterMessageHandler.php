<?php

namespace App\MessageHandler;

use App\Entity\Customer;

use App\Message\CustomerImporterMessage;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\EntityManager\CustomerManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CustomerImporterMessageHandler implements MessageHandlerInterface
{
    private CustomerManager $customerManager;
    private EntityManagerInterface $entityManager;

    public function __construct(CustomerManager $customerManager, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->customerManager = $customerManager;
    }

    public function __invoke(CustomerImporterMessage $message) : void
    {
        $this->customerManager->processDTO($message->getCustomerDTO(), $this->getExistingCustomer($message));
        $this->customerManager->flush();
    }

    private function getExistingCustomer(CustomerImporterMessage $message) : ?Customer
    {
        return $this->entityManager
        ->getRepository(Customer::class)
        ->findOneBy(['email' => $message->getCustomerDTO()->email]);
    }
}
