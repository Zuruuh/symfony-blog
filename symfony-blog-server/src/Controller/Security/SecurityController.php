<?php

namespace App\Controller\Security;

use App\Common\Http\AbstractController;
use App\Entity\User;
use App\Form\Security\LoginAsType;
use App\Form\Security\LoginType;
use App\Form\User\UserType;
use App\Manager\UserManager;
use App\Normalizer\User\SelfNormalizer;
use App\Repository\UserRepository;
use App\Service\Auth\JWTService;
use App\Service\Auth\UserAuthService;
use App\Voter\UserVoter;
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
        return $this->serializeToJson(SelfNormalizer::class, $this->getUser());
    }

    #[Route('/login', methods: ['POST'])]
    public function login(UserRepository $userRepository, Hasher $hasher, JWTService $JWTService): Response
    {
        $form = $this->createAndSubmitForm(LoginType::class);

        if (!$form->isValid()) {
            return $this->displayForm($form);
        }

        /**
         * @var string $identifier
         * @var string $password
         */
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
    public function register(JWTService $JWTService, UserManager $userManager): Response
    {
        $form = $this->createAndSubmitForm(UserType::class);

        if (!$form->isValid()) {
            return $this->displayForm($form);
        }

        $user = $form->getData();
        $userManager = $userManager->forUser($user);

        $userManager->save();
        $this->em->flush();

        $userManager->sendVerificationEmail();
        $token = $JWTService->generate($user);

        return $this->json(['token' => $token]);
    }

    #[Route('/verify-account/{token}', methods: ['GET'])]
    public function verifyAccount(string $token, UserAuthService $authService): Response
    {
        $validated = $authService->verifyToken($token);
        if (!$validated) {
            throw $this->exception(404, $this->t('auth.invalid_verify_account_token', [], 'errors'));
        }

        $this->em->flush();

        return $this->void();
    }

    #[Route('/login-as', methods: ['POST'])]
    public function loginAs(UserRepository $userRepository, JWTService $JWTService): Response
    {
        $this->denyAccessUnlessGranted(User::SUPER_ADMIN_ROLE, $this->getUser());

        $form = $this->createAndSubmitForm(LoginAsType::class);

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
