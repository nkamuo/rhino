<?php
namespace App\Message\Chat\Role;
use App\CQRS\Query\CollectionCountQuery;
use App\Message\Chat\Composition\ChannelAwareMessageInterface;
use App\Message\Chat\Composition\ChannelAwareMessageTrait;

class CountRole extends CollectionCountQuery implements ChannelAwareMessageInterface{
    use ChannelAwareMessageTrait;
}