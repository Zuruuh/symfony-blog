<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    private const SUPPORTED_LOCALES = ['fr', 'en'];
    private const FALLBACK_LOCALE   = 'en';

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->headers->has('x-lang')) {
            $request->setLocale(self::FALLBACK_LOCALE);

            return;
        }

        $locale = $request->headers->get('x-lang');
        $locale = in_array(strtolower($locale), self::SUPPORTED_LOCALES) ? $locale : self::FALLBACK_LOCALE;

        $request->setLocale($locale);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 100],
            ],
        ];
    }
}
