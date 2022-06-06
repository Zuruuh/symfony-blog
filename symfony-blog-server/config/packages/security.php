<?php

use App\Entity\User;
use App\Common\Constant\UserRoles;
use App\Security\Authenticator\TokenAuthenticator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Config\SecurityConfig;


const USER_PROVIDER = 'app_user_provider';
return static function (SecurityConfig $security, ContainerConfigurator $container)
{

    $security->enableAuthenticatorManager(true);
    if ($container->env() === 'prod') {
        $security
            ->passwordHasher(PasswordAuthenticatedUserInterface::class)
                ->algorithm('argon2')
        ;
    } else {
        $security
            ->passwordHasher(PasswordAuthenticatedUserInterface::class)
                ->algorithm('md5')
        ;
    }

    $security
        ->provider(USER_PROVIDER)
        ->entity()
            ->class(User::class)
            ->property('email')
    ;

    $security
        ->firewall('dev')
            ->pattern('^/(_(profiler|wdt)|css|images|resources|js|scripts)/')
            ->security(false)
    ;

    $security
        ->firewall('auth')
            ->pattern('^/auth/(register|login)$')
            ->lazy(true)
            ->security(false)
            ->stateless(true)
            ->provider(USER_PROVIDER)
            ->loginThrottling()
            ->maxAttempts(5)
            ->interval('10 minutes')
    ;

    $security
        ->firewall('main')
            ->pattern('^/')
            ->lazy(true)
            ->stateless(true)
            ->provider(USER_PROVIDER)
            ->customAuthenticators([
                TokenAuthenticator::class
            ])
    ;

    $roles = array_filter(UserRoles::ROLES, fn (string $role) => $role !== UserRoles::SUPER_ADMIN_ROLE);
    $security->roleHierarchy(UserRoles::SUPER_ADMIN_ROLE, $roles);
    $security->roleHierarchy(User::ADMIN_ROLE, [User::USER_ROLE]);
};
