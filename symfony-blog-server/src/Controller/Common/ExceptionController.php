<?php

namespace App\Controller\Common;

use App\Common\Http\AbstractController;
use Throwable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionController extends AbstractController
{
    public function __invoke(\Throwable $exception): Response
    {
        return $this->show($exception);
    }

    public function show(Throwable $exception): Response
    {
        $data = $this->getSerializedError($exception);

        return new JsonResponse($data, $this->getStatusCode($exception));
    }

    private function getSerializedError(Throwable $throwable): array
    {
        $data = [
            'message' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'previous' => null === $throwable->getPrevious() ? null : $this->getSerializedError($throwable->getPrevious()),
            'trace' => $throwable->getTrace(),
            'trace_as_string' => $throwable->getTraceAsString(),
        ];

        if ($throwable instanceof HttpException) {
            $data = [
                ...$data,
                'status' => $throwable->getStatusCode(),
                'headers' => $throwable->getHeaders(),
            ];
        }

        return $data;
    }

    private function getStatusCode(Throwable $throwable): int
    {
        if ($throwable instanceof HttpException) {
            return $throwable->getStatusCode();
        }

        return 500;
    }
}