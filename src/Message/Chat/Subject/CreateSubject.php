<?php

namespace App\Message\Chat\Subject;

use App\CQRS\Command\EntityCreationCommandInterface;
final class CreateSubject extends SubjectMutationCommand implements EntityCreationCommandInterface
{
    
}
