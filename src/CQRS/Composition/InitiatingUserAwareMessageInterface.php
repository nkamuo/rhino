<?php
namespace App\CQRS\Composition;
use App\CQRS\Query\QueryInterface;
use Symfony\Component\Uid\Ulid;


interface InitiatingUserAwareMessageInterface extends QueryInterface{

    /**
     * The Id of the user Initialting this Message
     * @return Ulid
     */
    public function getInitiatingUserId(): ?Ulid;
}