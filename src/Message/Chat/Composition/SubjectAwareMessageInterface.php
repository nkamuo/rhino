<?php
namespace App\Message\Chat\Composition;
use App\CQRS\MessageInterface;
use Symfony\Component\Uid\Ulid;

interface SubjectAwareMessageInterface extends MessageInterface{

    public function getSubjectId(): ?Ulid;
    
}