<?php
namespace App\Message\Chat\Subject;

use Symfony\Component\Uid\Ulid;


class FindSubjectById{
    public function __construct(private readonly Ulid $id){

    }


    public function getId(){
        return $this->id;
    }
}