<?php
namespace App\CQRS;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface as BaseMessageBusInterface;

class QueryBus extends MessageBus implements QueryBusInterface{
    use HandleTrait;

    /**
     */
    public function __construct(private BaseMessageBusInterface $queryBus) {
        parent::__construct($queryBus);
        $this->messageBus = $queryBus;
    }

	/**
	 * @param object $query
	 * @param mixed $stamps
	 * @return mixed
	 */
	public function query(object $query, array $stamps = array()): mixed {
        return $this->handle($query);
	}
}