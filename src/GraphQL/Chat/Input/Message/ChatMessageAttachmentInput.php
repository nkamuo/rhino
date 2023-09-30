<?php
namespace App\GraphQL\Chat\Input\Message;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input()]
class ChatMessageAttachmentInput{
    
    #[GQL\Field()]
    public ?string $label = null;

    #[GQL\Field(type: 'File')]
    public ?string $file = null;
}