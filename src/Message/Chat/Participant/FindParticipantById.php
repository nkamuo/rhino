<?php
namespace App\Message\Chat\Participant;

use Symfony\Component\Uid\Ulid;


class FindParticipantById{
    public function __construct(private readonly Ulid $id){

    }


    public function getId(){
        return $this->id;
    }
}