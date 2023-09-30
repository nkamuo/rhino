<?php
namespace App\Message\Chat\Message;
use App\CQRS\Query\CollectionSearchQuery;
use App\Message\Chat\Composition\ActiveParticipantAwareMessageInterface;
use App\Message\Chat\Composition\ActiveParticipantAwareMessageTrait;
use App\Message\Chat\Composition\ChannelAwareMessageInterface;
use App\Message\Chat\Composition\ChannelAwareMessageTrait;
use App\Message\Chat\Composition\ParticipantAwareMessageInterface;
use App\Message\Chat\Composition\ParticipantAwareMessageTrait;
use App\Message\Chat\Composition\SubjectAwareMessageInterface;
use App\Message\Chat\Composition\SubjectAwareMessageTrait;

class SearchChatMessage extends CollectionSearchQuery implements ChannelAwareMessageInterface, ParticipantAwareMessageInterface, SubjectAwareMessageInterface, ActiveParticipantAwareMessageInterface{
    use ChannelAwareMessageTrait;
    use ParticipantAwareMessageTrait;
    use SubjectAwareMessageTrait;
    use ActiveParticipantAwareMessageTrait;
}