<?php

namespace App\Controller;

use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth', name: 'auth.')]
class AuthController extends AbstractController
{

    public function __construct(
        private AuthService $authService
    ) {
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        return $this->authService->register($request);
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): Response
    {
        return $this->authService->login($request);
    }
}
