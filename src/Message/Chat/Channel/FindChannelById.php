<?php
namespace App\Message\Chat\Channel;

use Symfony\Component\Uid\Ulid;


class FindChannelById{
    public function __construct(private readonly Ulid $id){

    }


    public function getId(){
        return $this->id;
    }
}