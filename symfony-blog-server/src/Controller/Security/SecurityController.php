<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\Security\LoginAsType;
use App\Form\Security\LoginType;
use App\Form\User\UserType;
use App\Normalizers\User\SelfNormalizer;
use App\Repository\UserRepository;
use App\Common\AbstractController;
use App\Service\Auth\JWTService;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hasher;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth')]
class SecurityController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function me(): Response
    {
        $data = $this->serialize(SelfNormalizer::class, $this->getUser());

        return $this->json($data);
    }

    #[Route('/login', methods: ['POST'])]
    public function login(UserRepository $userRepository, Hasher $hasher, JWTService $JWTService): Response
    {
        $form = $this
            ->createForm(LoginType::class)
            ->submit($this
                ->getRequest()
                ->request
                ->all());

        if (!$form->isValid()) {
            return $this->displayForm($form);
        }

        list('usernameOrEmail' => $identifier, 'password' => $password) = $form->getData();

        $user = $userRepository->findByUniqueIdentifier($identifier);
        if (!$user || !$hasher->isPasswordValid($user, $password)) {
            $form->addError(new FormError(
                $this->t('auth.invalid_credentials', [], 'errors')
            ));

            return $this->displayForm($form);
        }

        $token = $JWTService->generate($user);

        return $this->json(['token' => $token]);
    }

    #[Route('/register', methods: ['POST'])]
    public function register(JWTService $JWTService, Hasher $hasher): Response
    {
        $form = $this
            ->createForm(UserType::class)
            ->submit($this
                ->getRequest()
                ->request
                ->all());

        if (!$form->isValid()) {
            return $this->displayForm($form);
        }
        $user = $form->getData();

        $password = $user->getPassword();
        $password = $hasher->hashPassword($user, $password);
        $user->setPassword($password);

        $this->em->persist($user);
        $this->em->flush();

        $token = $JWTService->generate($user);

        return $this->json(['token' => $token]);
    }

    #[Route('/login-as', methods: ['POST'])]
    public function loginAs(UserRepository $userRepository, JWTService $JWTService): Response
    {
        dump($this->getUser()->getRoles());
        $this->denyAccessUnlessGranted(User::SUPER_ADMIN_ROLE, $this->getUser());

        $form = $this
            ->createForm(LoginAsType::class)
            ->submit($this
                ->getRequest()
                ->request
                ->all()
            );

        if (!$form->isValid()) {
            return $this->displayForm($form);
        }

        $identifier = $form->get('usernameOrEmail')->getData();
        $user = $userRepository->findByUniqueIdentifier($identifier);

        if (!$user) {
            $message = $this->t('user.identifier_not_found', [], 'errors');

            throw $this->exception(404, $message);
        }

        $token = $JWTService->generate($user);

        return $this->json(['token' => $token]);
    }
}
