<?php

namespace App\Tests;

use App\Entity\Customer;
use App\Service\CustomerImporter;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Component\Validator\Validation;
use App\Service\CustomerImporter\ImporterStats;
use App\Service\CustomerImporter\DTO\CustomerDTO;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\CustomerImporter\Interface\CustomerDataProviderInterface;

class CustomerImporterTest extends DatabaseDependantTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
    }

    /** @test */
    public function testImportingOneValidCustomer() : void
    {
        $customerImporter = new CustomerImporter($this->mockCustomerProvider([$validCustomerDto = $this->makeValidDto()]), $this->prepareValidator(), $this->kernelContainer->get('messenger.default_bus'));
        $this->assertStatsAreEqual(new ImporterStats(1, 0), $customerImporter->import());
        $successfullyImportedCustomer = $this->entityManager
        ->getRepository(Customer::class)
        ->findOneBy(['email' => $validCustomerDto->email]);
        $this->assertNotNull($successfullyImportedCustomer);
    }

    public function testImportingOneEmptyCustomer() : void
    {
        $customerImporter = new CustomerImporter($this->mockCustomerProvider([$this->makeEmptyDto()]), $this->prepareValidator(), $this->kernelContainer->get('messenger.default_bus'));
        $this->assertStatsAreEqual(new ImporterStats(0, 1), $customerImporter->import());
    }

    public function testImportingNoCustomers() : void
    {
        $customerImporter = new CustomerImporter($this->mockCustomerProvider([]), $this->prepareValidator(), $this->kernelContainer->get('messenger.default_bus'));
        $this->assertStatsAreEqual(new ImporterStats(0, 0), $customerImporter->import());
    }

    public function testImportingOneInvalidCustomer() : void
    {
        $customerImporter = new CustomerImporter($this->mockCustomerProvider([$this->makeInvalidDto()]), $this->prepareValidator(), $this->kernelContainer->get('messenger.default_bus'));
        $this->assertStatsAreEqual(new ImporterStats(0, 1), $customerImporter->import());
    }

    public function testImportingValidAndInvalidCustomers() : void
    {
        $customerImporter = new CustomerImporter($this->mockCustomerProvider([$this->makeValidDto(), $this->makeInvalidDto()]), $this->prepareValidator(), $this->kernelContainer->get('messenger.default_bus'));
        $this->assertStatsAreEqual(new ImporterStats(1, 1), $customerImporter->import());
    }

    public function testExistingCustomerBeingUpdated() : void
    {
        $customerImporter = new CustomerImporter($this->mockCustomerProvider([$validCustomerDto = $this->makeValidDto()]), $this->prepareValidator(), $this->kernelContainer->get('messenger.default_bus'));
        $customerImporter->import();
        $validCustomerDto->username = "Alex";
        $newCustomerImporter = new CustomerImporter($this->mockCustomerProvider([$validCustomerDto]), $this->prepareValidator(), $this->kernelContainer->get('messenger.default_bus'));
        $newCustomerImporter->import();
        $existingCustomer = $this->entityManager
        ->getRepository(Customer::class)
        ->findOneBy(['email' => $validCustomerDto->email]);
        $this->assertEquals($validCustomerDto->username, $existingCustomer->getUsername());
    }
    
    private function assertStatsAreEqual(ImporterStats $expectedStats, ImporterStats $actualStats) : void
    {
        $this->assertInstanceOf(ImporterStats::class, $actualStats);
        $this->assertEquals($expectedStats->new, $actualStats->new);
        $this->assertEquals($expectedStats->invalid, $actualStats->invalid);
    }

    /** @param CustomerDTO[] $customerDTOs */
    private function mockCustomerProvider(array $customerDTOs) : CustomerDataProviderInterface
    {
        $mock = $this->createMock(CustomerDataProviderInterface::class);
        $mock->method('getCustomersData')->willReturn($customerDTOs);

        return $mock;
    }
    
    private function makeEmptyDto() : CustomerDTO
    {
        return new CustomerDTO();
    }

    
    private function makeValidDto() : CustomerDTO
    {
        $validDTO = new CustomerDTO();
        $validDTO->firstName = "Johh";
        $validDTO->lastName = "Doe";
        $validDTO->country = "Russia";
        $validDTO->city = "Vladivostok";
        $validDTO->gender = "male";
        $validDTO->email = "johndoeValidDtoUser@inbox.com";
        $validDTO->username = "johndoe55";
        $validDTO->phone = "07-7199-7065";
        return $validDTO;
    }

    private function makeInvalidDto() : CustomerDTO
    {
        $validDTO = new CustomerDTO();
        $validDTO->firstName = "Johh";
        $validDTO->lastName = "Doe";
        $validDTO->country = "Russia";
        $validDTO->city = "Vladivostok";
        $validDTO->gender = "attack helicopter";
        $validDTO->email = "johndoeValidDtoUser@inbox.com";
        $validDTO->username = "johndoe55";
        $validDTO->phone = "07-7199-7065";
        return $validDTO;
    }
    
    private function prepareValidator() : ValidatorInterface
    {
        return Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }
}
