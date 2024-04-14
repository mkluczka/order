<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class ExceptionEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof HandlerFailedException) {
            $wrapped = $throwable->getWrappedExceptions();
            $throwable = reset($wrapped);
        }

        if ($throwable instanceof ValidationFailedException) {
            $validationResponse = [];
            foreach ($throwable->getViolations() as $violation) {
                $validationResponse[$violation->getPropertyPath()] = $violation->getMessage();
            }

            $event->setResponse(
                new JsonResponse($validationResponse, 400),
            );
        }

        if ($throwable instanceof \DomainException) {
            $event->setResponse(
                new JsonResponse(['error' => $throwable->getMessage()], 409),
            );
        }
    }
}
