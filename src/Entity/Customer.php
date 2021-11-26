<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)\
 * @ORM\Table(name="customers")
 * @UniqueEntity("email")
 */
class Customer
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show", "index"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"show", "index"})
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show", "index"})
     */
    private string $country;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     */
    private string $gender;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     */
    private string $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     */
    private string $phone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
    /**
     * @Groups({"index", "show"})
     */
    public function getFullName() : ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
