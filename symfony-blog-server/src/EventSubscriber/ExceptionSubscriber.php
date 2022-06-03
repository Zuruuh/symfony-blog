<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

class ExceptionSubscriber implements  EventSubscriberInterface
{
    public function __construct(
        private KernelInterface $kernel,
    ) {}

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = $event->getResponse();

        if (method_exists($exception, 'getStatusCode')) {
            $code = $exception->getStatusCode();
        } else if (method_exists($exception, 'getCode')) {
            $code = $exception->getCode();
        } else {
            $code = 500;
        }

        $data = [
            'message' => $exception->getMessage(),
            'code' => $code,
        ];

        if ($this->kernel->getEnvironment() === 'dev') {
            $data['trace'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'exception' => $exception::class,
            ];
        }

        $json = json_encode($data);

        if (!$response) {
            $response = new Response($json, $code ?: 500, ['Content-Type' => 'application/json']);
        }

        (function () use ($json) {
            $this->content = $json;
        })->call($response);

        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }
}