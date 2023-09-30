<?php
namespace App\Service\Chat\Exception;
use App\Entity\Chat\ChatChannel;

class ChatChannelParticipanResolutionException extends \Exception
{
    public function __construct(ChatChannel|string|null $channel = null, ?string $message = null){

        if(is_string($channel)){
            parent::__construct($channel);
            return;
        }

        $message ??= '';
        if($channel instanceof ChatChannel){
            $message = "Could not resolve the current participant for the channel {$channel->getCode()}";
        }

        parent::__construct($message);
    }
}