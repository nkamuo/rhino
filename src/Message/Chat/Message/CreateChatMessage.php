<?php

namespace App\Message\Chat\Message;

use App\CQRS\Command\EntityCreationCommandInterface;
final class CreateChatMessage extends ChatMessageMutationCommand implements EntityCreationCommandInterface
{
    
}
