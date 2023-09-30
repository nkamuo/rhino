<?php
namespace App\CQRS\Command;
use App\CQRS\Command\Result\CQRSEntityMutationResult;
use App\CQRS\Command\Result\CQRSEntryCreationCommandResult;
use App\CQRS\Command\Result\CQRSEntryUpdateCommandResult;
use App\CQRS\CommandBusInterface;
use Symfony\Component\Uid\Ulid;
class MutationCommandContext{


    private ?CQRSEntityMutationResult $result = null;

    public function __construct(private CommandBusInterface $commandBus, private ?string $clientMuationId = null){

    }


    public function dispatch(EntityMutationCommandInterface $command, array $stamps = []): CQRSEntityMutationResult{

        if(!($uid = $command->getUid())){
            $uid = new Ulid();
            $command->setUid($uid);
        }

        $this->commandBus->dispatch($command, $stamps);

        return $this->result ??= (
            ($command instanceof EntityCreationCommandInterface) ? 
            new CQRSEntryCreationCommandResult($uid, $this->clientMuationId)
            :
            new CQRSEntryUpdateCommandResult($uid, $this->clientMuationId)
        );

    }
    
}