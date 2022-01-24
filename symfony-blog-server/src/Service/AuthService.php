<?php

namespace App\Service;

use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\ApiSessionAuthenticator;
use App\Util\FormGenerator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hasher;
use Predis\Client as Redis;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Cookie;

class AuthService
{

    public const INVALID_CREDENTIALS = 'Invalid credentials. Make sur your login & password are correct.';

    public function __construct(
        private FormGenerator $formGenerator,
        private Hasher $hasher,
        private EntityManagerInterface $em,
        private UserService $userService,
        private UserRepository $userRepository,
        private Redis $redis,
    ) {
    }

    public function register(Request $request): Response
    {
        $user = new User();
        $this->formGenerator->handleForm(RegistrationFormType::class, $request, $user);

        $this->userService->updatePassword($user, $user->getPassword());
        $this->userService->save($user);

        $authCookie = $this->logInSession($user);

        $response = new JsonResponse(['valid' => true], 201);
        $response->headers->setCookie($authCookie);

        return $response;
    }

    public function login(Request $request): Response
    {
        $form = $this->formGenerator->handleForm(LoginFormType::class, $request);
        $login = $form->get('usernameOrEmail')->getData();

        $user = $this->userRepository->findOneBy([
            (str_contains($login, '@')
                ? 'email'
                : 'username'
            ) => $login
        ]);

        if (!$user) {
            $this->formGenerator->addFieldError($form, 'usernameOrEmail', self::INVALID_CREDENTIALS);
        } else if (!$this->hasher->isPasswordValid($user, $form->get('password')->getData())) {
            $this->formGenerator->addFieldError($form, 'usernameOrEmail', self::INVALID_CREDENTIALS);
        }
        $this->formGenerator->validateForm($form);

        $authCookie = $this->logInSession($user);
        $response = new JsonResponse(['valid' => true]);
        $response->headers->setCookie($authCookie);

        return $response;
    }

    public function logInSession(User $user): Cookie
    {
        $sessid = 'sess_' . Uuid::uuid4() . (new DateTime())->format('Y-m-d/H:i:s');
        $this->redis->set($sessid, $user->getId());

        return new Cookie(ApiSessionAuthenticator::AUTH_SESSID_COOKIE, $sessid);
    }
}
