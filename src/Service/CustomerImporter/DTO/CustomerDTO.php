<?php

namespace App\Service\CustomerImporter\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CustomerDTO
{
    /**
     * @Assert\NotBlank
     */
    public string $firstName;
    /**
     * @Assert\NotBlank
     */
    public string $lastName;
    /**
     * @Assert\NotBlank
     */
    public string $country;
    /**
     * @Assert\NotBlank
     */
    public string $city;
    /**
     * @Assert\Choice({"male", "female"})
     */
    public string $gender;
    /**
     * @Assert\Email
     */
    public string $email;
    /**
     * @Assert\NotBlank
     */
    public string $username;
    /**
     * @Assert\Regex("/^(\+?\(61\)|\(\+?61\)|\+?61|\(0[1-9]\)|0[1-9])?( ?-?[0-9]){7,9}$/")
     * checks australian phone number, doesnt allow "00" region code;
     */
    public string $phone;
}
