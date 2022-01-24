<?php

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private string $env
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $exceptionEvent): void
    {
        $exception = $exceptionEvent->getThrowable();
        if ($exception instanceof HttpException) {
            if ($exception instanceof BadRequestHttpException) {
                $exceptionEvent->setResponse(new JsonResponse([
                    'errors' => json_decode($exception->getMessage(), true)
                ], $exception->getStatusCode()));

                return;
            }
            $exceptionEvent->setResponse(new JsonResponse([
                $exception->getMessage()
            ], $exception->getStatusCode()));

            return;
        }

        $stackTrace = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];

        // $exceptionEvent->setResponse(new JsonResponse(array_merge(
        //     ['message' => 'An unexpected error occurred'],
        //     $this->env !== 'prod' ? ['stackTrace' => $stackTrace] : []
        // )));
    }
}
