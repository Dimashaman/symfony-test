<?php

namespace App\Tests;

use App\Tests\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DatabaseDependantTestCase extends KernelTestCase
{
    protected mixed $entityManager;
    protected mixed $kernelContainer;
        
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime($kernel);
        $this->kernelContainer = $kernel->getContainer();
        $this->entityManager = $this->kernelContainer->get('doctrine')->getManager();
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
