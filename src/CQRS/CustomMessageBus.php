<?php
namespace App\CQRS;


use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

class CustomMessageBus implements MessageBusInterface
{
    private $defaultBus;

    public function __construct(MessageBusInterface $defaultBus)
    {
        $this->defaultBus = $defaultBus;
    }

    public function dispatch($message, array $stamps = []): Envelope
    {
        // Add the TransportStamp to specify the transport as "sync"
        $stamps[] = new TransportNamesStamp(['sync']);
        return $this->defaultBus->dispatch($message, $stamps);
    }
}
