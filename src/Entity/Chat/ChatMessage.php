<?php

namespace App\Entity\Chat;

use App\Repository\Chat\ChatMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ChatMessageRepository::class)]
class ChatMessage extends AbstractMessage
{
}
