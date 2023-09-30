<?php
namespace App\Message\Chat\Message;

use Symfony\Component\Uid\Ulid;


class FindChatMessageById{
    public function __construct(private readonly Ulid $id){

    }


    public function getId(){
        return $this->id;
    }
}