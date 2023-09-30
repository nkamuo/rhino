<?php

namespace App\Message\Chat\Role;

use App\CQRS\Command\EntityMutationCommandInterface;
use App\CQRS\Command\EntityMutationCommandTrait;
use App\Entity\Chat\ChatChannelRole;
use Symfony\Component\Uid\Ulid;

final class DeleteRole implements EntityMutationCommandInterface
{
    
    use EntityMutationCommandTrait;

    public function __construct(ChatChannelRole|Ulid|null $role = null){

        if($role !== null){

            if($role instanceof ChatChannelRole)
                $this->setUid($role->getId());
            else
                $this->setUid($role);
        }

    }
}
