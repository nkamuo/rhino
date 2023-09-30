<?php
namespace App\Message\Chat\Participant;
use App\CQRS\Query\CollectionCountQuery;
use App\Message\Chat\Composition\ChannelAwareMessageInterface;
use App\Message\Chat\Composition\ChannelAwareMessageTrait;

class CountParticipant extends CollectionCountQuery implements ChannelAwareMessageInterface{
    use ChannelAwareMessageTrait;
}