<?php
namespace App\Message\Chat\Participant;
use App\CQRS\Query\CollectionSearchQuery;
use App\Message\Chat\Composition\ChannelAwareMessageInterface;
use App\Message\Chat\Composition\ChannelAwareMessageTrait;

class SearchParticipant extends CollectionSearchQuery implements ChannelAwareMessageInterface{
    use ChannelAwareMessageTrait;
}