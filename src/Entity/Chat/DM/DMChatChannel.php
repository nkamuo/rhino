<?php

namespace App\Entity\Chat\DM;

use App\Entity\Chat\ChatChannel;
use App\Repository\Chat\DM\DMChatChannelRepository;
use App\Entity\Chat\AbstractChatChannel;
use App\Entity\Chat\AbstractChatParticipant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type(interfaces:['AbstractChatChannel'])]
#[ORM\Entity(repositoryClass: DMChatChannelRepository::class)]
class DMChatChannel extends AbstractChatChannel
{

    public function addParticipant(AbstractChatParticipant $participant): static{
        if(!($participant  instanceof Conversation)){
            throw new \InvalidArgumentException("A DMChatrChannel can only referennce DMChatConversations as Participants");
        }

        $participants = new ArrayCollection($this->getParticipants()->toArray());

        if(!$participants->contains($participant)){
            $participants->add($participant);
        }
        if($participants->count() > 2){
            throw new \LogicException("A DMChatChannel can contain exactly 2 conversations");
        }
        return parent::addParticipant($participant);
    }

}
