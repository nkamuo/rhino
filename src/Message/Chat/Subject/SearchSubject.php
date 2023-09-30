<?php
namespace App\Message\Chat\Subject;
use App\CQRS\Query\CollectionSearchQuery;
use App\Message\Chat\Composition\ChannelAwareMessageInterface;
use App\Message\Chat\Composition\ChannelAwareMessageTrait;

class SearchSubject extends CollectionSearchQuery implements ChannelAwareMessageInterface{
    use ChannelAwareMessageTrait;
}