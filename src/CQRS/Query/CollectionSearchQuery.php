<?php
namespace App\CQRS\Query;
use App\CQRS\Composition\InitiatingUserAwareMessageInterface;
use App\CQRS\Composition\InitiatingUserAwareMessageTrait;

class CollectionSearchQuery implements InitiatingUserAwareMessageInterface{
    use InitiatingUserAwareMessageTrait;
 
    public function __construct(
        private readonly array|string|null $filter = null,
        private int $offset = 0,
        private ?int $limit = null){

    }

    public function getFilter(){
        return $this->filter;
    }


    public function getOffset(): int{
        return $this->offset;
    }

    public function getLimit(): ?int{
        return $this->limit;
    }
}