<?php
namespace App\Message\Chat\Subject;
use App\CQRS\Query\CollectionCountQuery;
use App\Message\Chat\Composition\ChannelAwareMessageInterface;
use App\Message\Chat\Composition\ChannelAwareMessageTrait;

class CountSubject extends CollectionCountQuery implements ChannelAwareMessageInterface{
    use ChannelAwareMessageTrait;
}