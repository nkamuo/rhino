<?php

namespace App\Entity\Chat;

use App\Repository\Chat\ChatChannelRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;


#[GQL\Type(interfaces:['AbstractChatChannel'])]
#[ORM\Entity(repositoryClass: ChatChannelRepository::class)]
class ChatChannel extends AbstractChatChannel 
{

    #[GQL\Field(name: 'participantCount')]
    public function getParticipantCount(?string $role = null): ?int
    {
        if ($role) {
            return $this->getParticipants()
                ->filter(fn (ChatParticipant $participant) => $participant->getRole()?->getCode() === $role)
                ->count();
        }
        return $this->getParticipants()->count();
        // return $this->participantCount;
    }
  
}
