<?php

namespace App\GraphQL\Chat\Resolver;

use App\CQRS\Command\Result\CQRSEntryCreationCommandResult;
use App\CQRS\Command\Result\CQRSEntryUpdateCommandResult;
use App\CQRS\CommandBusInterface;
use App\CQRS\QueryBusInterface;
use App\Entity\Chat\AbstractChatParticipant;
use App\Entity\Chat\ChatParticipant;
use App\GraphQL\Chat\Input\Message\ChatMessageCreationInput;
use App\GraphQL\Chat\Input\Message\ChatMessageUpdateInput;
use App\Message\Chat\Message\CreateChatMessage;
use App\Message\Chat\Message\DeleteChatMessage;
use App\Message\Chat\Message\UpdateChatMessage;
use App\Service\Chat\ChatContextResolverInterface;
use App\Service\File\UploaderInterface;
use Overblog\GraphQLBundle\Annotation\Arg;
use Overblog\GraphQLBundle\Annotation\Mutation;
use Overblog\GraphQLBundle\Annotation\Provider;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Ulid;

#[Provider(
    targetMutationTypes: ['Mutation'],
)]
class ChatMessageMutationResolver
{

    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private ChatContextResolverInterface $chatContextResolver,
        private UploaderInterface $uploader,
    ) {
    }


    #[Mutation]
    public function createNewChatMessage(ChatMessageCreationInput $input, ?string $clientMutationId = null): CQRSEntryCreationCommandResult
    {

        if (!(trim($input->body) || count($input->attachments))) {
            throw new UserError("Message must specify a non-empty message body or contain at least one attachment ");
        }

        $participant = $this->getParticipantByChannelId($input->channelId);
        $command = new CreateChatMessage();
        $command
            ->setChannelId($input->channelId)
            ->setSubjectId($input->subjectId)
            ->setParticipantId($participant->getId())
            ->setBody($input->body);

        if ($input->attachments) {
            foreach ($input->attachments as $attachment) {
                $uri = $this->handleFileUpload($participant, $attachment->file);
                $command->addAttachment($uri);
            }
        }
        return $this->commandBus->getMutationContext($clientMutationId)->dispatch($command);
    }


    #[Mutation()]
    #[Arg(name: 'clientId',  type: 'Ulid!')]
    #[Arg(name: 'input', type: 'ChatMessageUpdateInput!')]
    #[Arg(name: 'clientMutationId', type: 'String')]
    public function updateChatMessage(Ulid $id, ChatMessageUpdateInput $input, ?string $clientMutationId = null): CQRSEntryUpdateCommandResult
    {

        // if( $input->code && $this->queryBus->query(new CountChatMessage(filter: ['code' => $input->code])) > 0){
        //     throw new UserError("ChatMessage ChatMessage With [code:{$input->code}] already exists");
        // }

        $participant = $this->getParticipantByChannelId($input->channelId);
        $command = new UpdateChatMessage($id);
        $command
            ->setChannelId($input->channelId)
            ->setSubjectId($input->subjectId)
            ->setParticipantId($participant->getId())
            ->setBody($input->body);
        return $this->commandBus->getMutationContext($clientMutationId)->dispatch($command);
    }


    #[Mutation()]
    #[Arg(
        name: 'clientId',
        type: 'Ulid!'
    )]
    #[Arg(
        name: 'clientMutationId',
        type: 'String'
    )]
    public function deleteChatMessage(Ulid $id, ?string $clientMutationId = null): ?bool
    {
        $command = new DeleteChatMessage($id);
        $this->commandBus->getMutationContext($clientMutationId)->dispatch($command);
        return null;
    }


    private function handleFileUpload(AbstractChatParticipant $participant, UploadedFile $file): string
    {
        $channel = $participant->getChannel();
        $path = sprintf('chat/%s', $channel->getId());
        $uri = $this->uploader->upload($file, $path);
        return $uri;
    }

    public function getParticipantByChannelId(Ulid $id): AbstractChatParticipant
    {
        return  $this->chatContextResolver->resolveCurrentChatParticipantForChannelId($id);
    }
}
