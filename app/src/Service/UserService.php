<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    /**
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function tableExists(): bool
    {
        return $this->userRepository->tableExists();
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function createUserTable(): void
    {
        $this->userRepository->createUserTable();
    }

    /**
     * @return void
     */
    public function seedUsers(): void
    {
        $this->userRepository->seedUsers();
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->find($id);
        if ($user) {
            $this->userRepository->remove($user);
        }
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $address
     * @return array|User[]
     */
    public function createUser(string $firstname, string $lastname, string $address): array
    {
        $user = new User();
        $user->setFirstName($firstname);
        $user->setLastName($lastname);
        $user->setAddress($address);

        // Validate the User entity
        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            return ['errors' => $errors];
        }

        $existingUser = $this->userRepository->findOneBy([
            'firstName' => $firstname,
            'lastName' => $lastname,
            'address' => $address,
        ]);

        if ($existingUser != null) {
            return ['existingUser' => true];
        }

        $this->userRepository->save($user);
        return ['user' => $user];
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->userRepository->findAll();
    }
}
