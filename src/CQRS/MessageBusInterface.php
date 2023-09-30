<?php
namespace App\CQRS;
use Symfony\Component\Messenger\Envelope;

interface MessageBusInterface{
    /**
     * @param object $message
     * @param array<\Symfony\Component\Messenger\Stamp\StampInterface> $stamps
     */
    public function dispatch(object $message,array $stamps = []): Envelope;
}