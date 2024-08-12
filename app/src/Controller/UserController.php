<?php

// src/Controller/UserController.php
namespace App\Controller;

use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserService;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

class UserController extends AbstractController
{
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * @param UserService $userService
     * @param CsrfTokenManagerInterface $csrfTokenManager
     */
    public function __construct(UserService $userService, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->userService = $userService;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    #[Route('/user', name: 'user_management')]
    public function request(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $errors = null;

        if (!$this->userService->tableExists()) {
            $this->userService->createUserTable();
            $this->userService->seedUsers();
        }

        if ($request->isMethod('POST')) {
            $token = $request->request->get('_token');
            if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('user_form', $token))) {
                $errors = ['Invalid CSRF token.'];
                $this->addFlash('error', 'Invalid CSRF token.');
            } else {
                $firstName = $request->request->get('firstname');
                $lastName = $request->request->get('lastname');
                $address = $request->request->get('address');

                $result = $this->userService->createUser($firstName, $lastName, $address);
                if (isset($result['errors'])) {
                    $errors = $result['errors'];
                } else if (isset($result['existingUser'])) {
                    $this->addFlash('error', 'User already exists');
                } else {
                    $this->addFlash('success', 'User added successfully!');
                }
            }
        }

        $users = $this->userService->getUsers();

        return $this->render('user.html.twig', [
            'method' => $request->getMethod(),
            'users' => $users,
            'errors' => $errors,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    #[Route('/user/delete/{id}', name: 'user_delete', methods: ['POST'])]
    public function deleteUser(Request $request, int $id)
    {
        $csrfToken = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete-user-' . $id, $csrfToken)) {
            $this->userService->deleteUser($id);
            $this->addFlash('success', 'User deleted successfully!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('user_management');
    }
}
