<?php
namespace App\Message\Chat\Role;
use App\CQRS\Query\CollectionSearchQuery;
use App\Message\Chat\Composition\ChannelAwareMessageInterface;
use App\Message\Chat\Composition\ChannelAwareMessageTrait;

class SearchRole extends CollectionSearchQuery implements ChannelAwareMessageInterface{
    use ChannelAwareMessageTrait;
}