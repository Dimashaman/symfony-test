<?php

namespace App\Service\EntityManager;

use App\Entity\Customer;
use App\Service\CustomerImporter\DTO\CustomerDTO;
use Doctrine\ORM\EntityManagerInterface;

class CustomerManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function processDTO(CustomerDTO $customerDTO, ?Customer $customer = null): Customer
    {
        if (!$customer) {
            $customer = new Customer();
            $this->entityManager->persist($customer);
        }
        $customer->setFirstName($customerDTO->firstName);
        $customer->setLastName($customerDTO->lastName);
        $customer->setCountry($customerDTO->country);
        $customer->setCity($customerDTO->city);
        $customer->setGender($customerDTO->gender);
        $customer->setEmail($customerDTO->email);
        $customer->setUsername($customerDTO->username);
        $customer->setPhone($customerDTO->phone);

        return $customer;
    }

    public function flush() : void
    {
        $this->entityManager->flush();
    }
}
