<?php
namespace App\CQRS\Command\Result;

use Overblog\GraphQLBundle\Annotation\Deprecated;
use Overblog\GraphQLBundle\Annotation\Field;
use Overblog\GraphQLBundle\Annotation\Type;
use Symfony\Component\Uid\Ulid;

#[Type()]
class CQRSEntityMutationResult extends CQRSCommandResult{


    #[Field(type: 'Ulid')]
    #[Deprecated("use id instead")]
    private ?Ulid $uid = null;

    public function __construct(Ulid $uid, ?string $clientMutationId = null){
        $this->uid = $uid;
        parent::__construct($clientMutationId);
    }


    public function getUid(): ?Ulid{
        return $this->uid;
    }

    #[Field(name: 'id', type: 'Ulid')]
    public function getId(): ?Ulid{
        return $this->getUid();
    }

}