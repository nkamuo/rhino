<?php
namespace App\Message\Chat\Channel;
use App\CQRS\Query\CollectionSearchQuery;
use App\Message\Chat\Composition\ActiveUserAwareMessageInterface;
use App\Message\Chat\Composition\ActiveUserAwareMessageTrait;

class SearchChannel extends CollectionSearchQuery implements ActiveUserAwareMessageInterface{
    use ActiveUserAwareMessageTrait;
}