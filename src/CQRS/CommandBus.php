<?php
namespace App\CQRS;

use App\CQRS\Command\MutationCommandContext;
use App\CQRS\Command\Result\CQRSEntryCreationCommandResult;
use Symfony\Component\Messenger\MessageBusInterface as BaseMessageBusInterface;
use Symfony\Component\Uid\Factory\UlidFactory;

class CommandBus extends MessageBus implements CommandBusInterface{


    /**
     * @var array<string,CQRSEntryCreationCommandResult>
     */
    private array $results = [];

    
    /**
     * @var array<string,MutationCommandContext>
     */
    private array $mutationContexts = [];

    
    public function __construct(private BaseMessageBusInterface $commandBus,private UlidFactory $ulidFactory) {
        parent::__construct($commandBus);
    }

    public function getMutationContext(?string $clientMutationId = null): MutationCommandContext{

        if( is_string($clientMutationId) && array_key_exists($clientMutationId,$this->mutationContexts)){
            return $this->mutationContexts[$clientMutationId];
        }

        $context = new MutationCommandContext($this, $clientMutationId);

        if(is_string($clientMutationId))
            return $this->mutationContexts[$clientMutationId] = $context;

        return $context;
    }

    public function dispatchContextual(EntityCreationCommandInterface $command, ?string $clientMuationId): CQRSEntryCreationCommandResult{
        return $this->getMutationContext($clientMuationId)->dispatch($command);
    }
    

    // public function getOptimisticEntryCreationResult(?string $clientMutationId = null): CQRSEntryCreationCommandResult{

    //     if( is_string($clientMutationId) && array_key_exists($clientMutationId,$this->results)){
    //         return $this->results[$clientMutationId];
    //     }
        
    //     $ulid = $this->ulidFactory->create();
    //     $result = new CQRSEntryCreationCommandResult($ulid);

    //     if(is_string($clientMutationId))
    //         return $this->results[$clientMutationId] = $result;

    //     return $result;
    // }

}