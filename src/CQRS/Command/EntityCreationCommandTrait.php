<?php
namespace App\CQRS\Command;
use Symfony\Component\Uid\Ulid;



/**
 * @deprecated 0.0.0
 */
trait EntityCreationCommandTrait{
    private ?Ulid $uid = null;


    public function getUid(): ?Ulid{
        return $this->uid;
    }

    public function setUid(?Ulid $uid): static{
        $this->uid = $uid;
        return $this;
    }


}