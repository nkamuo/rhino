<?php
namespace App\GraphQL\Chat\Input\Message;

use Overblog\GraphQLBundle\Annotation\Field;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;


abstract class ChatMessageInput{

    #[Field(type: 'Ulid!')]
    public Ulid $channelId;

    
    #[Field(type: 'Ulid')]
    public ?Ulid $subjectId = null;

    
    #[Field]
    #[Length(max: 64)]
    #[NotBlank(allowNull: true)]
    public ?string $title = null;
    
    #[Field]
    #[NotNull()]
    #[NotBlank()]
    #[Length(max: 6400)]
    public ?string $body = null;

    
    /**
     * @var UploadedFile[]
     */
    #[Field(type: '[UploadFile!]')]
    public ?array $attachments = null;

    
}