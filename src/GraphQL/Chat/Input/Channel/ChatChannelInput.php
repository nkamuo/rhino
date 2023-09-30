<?php
namespace App\GraphQL\Chat\Input\Channel;

use Overblog\GraphQLBundle\Annotation\Field;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\HttpFoundation\File\UploadedFile;


abstract class ChatChannelInput{

    #[Field]
    #[Length(min: 4, max: 64)]
    public ?string $title = null;

    
}