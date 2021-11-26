<?php

namespace App\Service\CustomerImporter\Interface;

use App\Service\CustomerImporter\DTO\CustomerDTO;

interface CustomerDataProviderInterface
{
    /**
     * @return array<CustomerDTO>
     */
    public function getCustomersData(int $limit): array;
}
