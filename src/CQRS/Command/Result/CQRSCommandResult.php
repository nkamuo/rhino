<?php
namespace App\CQRS\Command\Result;
use Overblog\GraphQLBundle\Annotation\Field;
use Overblog\GraphQLBundle\Annotation\Type;


#[Type]
class CQRSCommandResult{

    #[Field()]
    private ?string $clientMutationId;


    public function __construct(?string $clientMutationId){
        $this->clientMutationId = $clientMutationId;
    }

    public function getClientMutationId(): ?string{
        return $this->clientMutationId;
    }

}