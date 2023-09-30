<?php

namespace App\Message\Chat\Subject;

use App\CQRS\Command\EntityMutationCommandInterface;
use App\CQRS\Command\EntityMutationCommandTrait;
use App\Entity\Chat\ChatSubject;
use Symfony\Component\Uid\Ulid;

final class DeleteSubject implements EntityMutationCommandInterface
{
    
    use EntityMutationCommandTrait;

    public function __construct(ChatSubject|Ulid|null $subject = null){

        if($subject !== null){

            if($subject instanceof ChatSubject)
                $this->setUid($subject->getId());
            else
                $this->setUid($subject);
        }

    }
}
