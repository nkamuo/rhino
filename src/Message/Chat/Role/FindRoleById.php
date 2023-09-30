<?php
namespace App\Message\Chat\Role;

use Symfony\Component\Uid\Ulid;


class FindRoleById{
    public function __construct(private readonly Ulid $id){

    }


    public function getId(){
        return $this->id;
    }
}