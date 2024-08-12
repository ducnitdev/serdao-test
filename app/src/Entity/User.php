<?php

// src/Entity/User.php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: App\Repository\UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'First name should not be blank.')]
    #[Assert\Length(max: 255, maxMessage: 'First name should not exceed {{ limit }} characters.')]
    private string $firstName;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Last name should not be blank.')]
    #[Assert\Length(max: 255, maxMessage: 'Last name should not exceed {{ limit }} characters.')]
    private string $lastName;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Address should not be blank.')]
    #[Assert\Length(max: 255, maxMessage: 'Address should not exceed {{ limit }} characters.')]
    private string $address;

    // Getter and Setter methods...

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }
}

