<?php
namespace App\Message\Chat\Channel;
use App\CQRS\Query\CollectionCountQuery;
use App\Message\Chat\Composition\ActiveUserAwareMessageInterface;
use App\Message\Chat\Composition\ActiveUserAwareMessageTrait;

class CountChannel extends CollectionCountQuery implements ActiveUserAwareMessageInterface{
    use ActiveUserAwareMessageTrait;
}