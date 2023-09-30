<?php
namespace App\CQRS;
use App\CQRS\Command\MutationCommandContext;
// use App\CQRS\Command\Result\CQRSEntryCreationCommandResult;


interface CommandBusInterface extends MessageBusInterface{

    public function getMutationContext(?string $clientMutationId = null): MutationCommandContext;
    // public function getOptimisticEntryCreationResult(?string $clientMutationId = null): CQRSEntryCreationCommandResult;
}