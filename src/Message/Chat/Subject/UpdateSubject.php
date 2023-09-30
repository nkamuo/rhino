<?php

namespace App\Message\Chat\Subject;

use App\CQRS\Command\EntityUpdateCommandInterface;
use App\Entity\Chat\ChatSubject;
use Symfony\Component\Uid\Ulid;
final class UpdateSubject extends SubjectMutationCommand implements EntityUpdateCommandInterface
{
    

    public function __construct(ChatSubject|Ulid|null $subject = null){

        if($subject !== null){

            if($subject instanceof ChatSubject)
                $this->setUid($subject->getId());
            else
                $this->setUid($subject);
        }

    }
}
