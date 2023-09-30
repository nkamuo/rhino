<?php
namespace App\Message\Chat\Composition;
use Symfony\Component\Uid\Ulid;



trait SubjectAwareMessageTrait{

    private ?Ulid $subjectId = null;

    public function getSubjectId(): ?Ulid{
        return $this->subjectId;
    }

    public function setSubjectId(?Ulid  $subjectId): static{
        $this->subjectId = $subjectId;
        return $this;
    }

}