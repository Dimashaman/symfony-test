<?php

namespace App\Service\CustomerImporter\Provider;

use GuzzleHttp\Client;
use App\Service\CustomerImporter\DTO\CustomerDTO;
use App\Service\CustomerImporter\Interface\CustomerDataProviderInterface;

class RandomUserApiDataProvider implements CustomerDataProviderInterface
{
    private const API_URI = 'https://randomuser.me/api';
    
    /** @var array<string> $params */
    private array $params = [
        'nat' => 'au',
        'results' => '150',
        'inc' => 'name, email, login, gender, location, phone',
    ];

    /**
     * @return array <CustomerDTO>
     */
    public function getCustomersData(int $limit) : array
    {
        $randomUserApiClient = new Client([
            // Base URI is used with relative requests
            'base_uri' => self::API_URI,
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        
        $this->params['results'] = strval($limit);
        
        $response = $randomUserApiClient->request('GET', '', [
            'query' => $this->params
        ]);

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        $customersResponseData = json_decode($response->getBody()->getContents(), true)['results'] ?? [];
        
        $customerDTOs = [];

        foreach ($customersResponseData as $customer) {
            $customerDTO = new CustomerDTO();
            $customerDTO->firstName = $customer['name']['first'] ?? [];
            $customerDTO->lastName = $customer['name']['last'] ?? [];
            $customerDTO->country = $customer['location']['country'] ?? [];
            $customerDTO->city = $customer['location']['city'] ?? [];
            $customerDTO->gender = $customer['gender'] ?? [];
            $customerDTO->email = $customer['email'] ?? [];
            $customerDTO->username = $customer['login']['username'] ?? [];
            $customerDTO->phone = $customer['phone'] ?? [];
            $customerDTOs[] = $customerDTO;
        }

        return $customerDTOs;
    }
}
