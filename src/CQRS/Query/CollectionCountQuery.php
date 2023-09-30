<?php
namespace App\CQRS\Query;
use App\CQRS\Composition\InitiatingUserAwareMessageInterface;
use App\CQRS\Composition\InitiatingUserAwareMessageTrait;

class CollectionCountQuery implements InitiatingUserAwareMessageInterface{
    use InitiatingUserAwareMessageTrait;
 
    public function __construct(private readonly array|string|null $filter = null, private ?array $additionalConstraints = null){

    }

    public function getFilter(){
        return $this->filter;
    }


    public function getAdditionalConstraints(): ?array{
        return $this->additionalConstraints;
    }

}