<?php

namespace App\Message;

use App\Service\CustomerImporter\DTO\CustomerDTO;

class CustomerImporterMessage
{
    private CustomerDTO $customerDto;

    public function __construct(CustomerDTO $customerDto)
    {
        $this->customerDto = $customerDto;
    }

    public function getCustomerDto() : CustomerDto
    {
        return $this->customerDto;
    }
}
