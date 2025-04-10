<?php

namespace App\EventSubscriber;

use App\Traits\ApiResponseTrait;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ApiValidationExceptionSubscriber implements EventSubscriberInterface
{
    use ApiResponseTrait;

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleValidationException', 50],
            ],
        ];
    }

    public function handleValidationException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof UnprocessableEntityHttpException) {

            $data = [
                'status' => 'error',
                'message' => $exception->getMessage()
            ];

            $response = new JsonResponse($data, $exception->getStatusCode());
            $event->setResponse($response);
        }
    }
}
