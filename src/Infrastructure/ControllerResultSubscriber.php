<?php declare(strict_types=1);

namespace App\Infrastructure;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class ControllerResultSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::VIEW => 'onView'];
    }

    public function onView(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        if (!is_object($controllerResult) || !str_starts_with($controllerResult::class, 'App\\Response\\')) {
            return;
        }

        $json = $this->serializer->serialize($controllerResult, 'json');

        $event->setResponse(
            new JsonResponse($json, json: true)
        );
    }
}
