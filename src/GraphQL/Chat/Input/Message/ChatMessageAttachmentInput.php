<?php
namespace App\GraphQL\Chat\Input\Message;

use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[GQL\Input()]
class ChatMessageAttachmentInput{
    
    #[GQL\Field()]
    public ?string $label = null;

    #[GQL\Field(type: 'UploadFile!')]
    public ?UploadedFile $file = null;
}