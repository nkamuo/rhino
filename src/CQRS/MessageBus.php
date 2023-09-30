<?php
namespace App\CQRS;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface as BaseMessageBusInterface;

class MessageBus implements MessageBusInterface{
    

    /**
     */
    public function __construct(private BaseMessageBusInterface $messageBus) {
    }
    
	/**
	 * @param object $query
	 * @param mixed $stamps
	 */
	public function dispatch(object $query,array $stamps = array()): Envelope {
        return $this->messageBus->dispatch($query, $stamps);
	}
}