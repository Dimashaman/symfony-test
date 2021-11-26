<?php

namespace App\Service;

use App\Message\CustomerImporterMessage;
use App\Service\CustomerImporter\ImporterStats;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\CustomerImporter\Interface\CustomerDataProviderInterface;

class CustomerImporter
{
    private CustomerDataProviderInterface $customerDataProvider;
    private ValidatorInterface $validator;
    private MessageBusInterface $bus;
    private int $limit = 150;

    public function __construct(
        CustomerDataProviderInterface $customerDataProvider,
        ValidatorInterface $validator,
        MessageBusInterface $bus
    ) {
        $this->customerDataProvider = $customerDataProvider;
        $this->validator = $validator;
        $this->bus = $bus;
    }

    public function import(int $limit = 150): ImporterStats
    {
        $this->limit = $limit;
        $customerDTOs = $this->customerDataProvider->getCustomersData($this->limit);
        $newCustomersCount = 0;
        $invalidCustomersCount = 0;

        foreach ($customerDTOs as $customerDTO) {
            $errors = $this->validator->validate($customerDTO);
            if (count($errors) > 0) {
                $invalidCustomersCount++;
                continue;
            }

            $newCustomersCount++;

            $this->bus->dispatch(new CustomerImporterMessage($customerDTO));
        }

        return new ImporterStats($newCustomersCount, $invalidCustomersCount);
    }
}
