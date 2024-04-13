<?php

declare(strict_types=1);

namespace App\Error;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ErrorListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
        // TODO: Implement getSubscribedEvents() method.
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof ValidationFailedException) {
            $validationResponse = [];
            foreach ($throwable->getViolations() as $violation) {
                $validationResponse[$violation->getPropertyPath()] = 'Invalid value';
            }

            $event->setResponse(
                new JsonResponse(
                    $validationResponse,
                    400,
                )
            );
        }
    }
}
