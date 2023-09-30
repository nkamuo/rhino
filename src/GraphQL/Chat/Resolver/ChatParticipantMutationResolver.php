<?php

namespace App\GraphQL\Chat\Resolver;

use App\CQRS\Command\Result\CQRSEntryCreationCommandResult;
use App\CQRS\Command\Result\CQRSEntryUpdateCommandResult;
use App\CQRS\CommandBusInterface;
use App\CQRS\QueryBusInterface;
use App\Entity\Chat\ChatParticipant;
use App\GraphQL\Chat\Input\Message\ChatMessageCreationInput;
use App\GraphQL\Chat\Input\Message\ChatMessageUpdateInput;
use App\Message\Chat\Message\CreateChatMessage;
use App\Message\Chat\Message\DeleteChatMessage;
use App\Message\Chat\Message\UpdateChatMessage;
use App\Service\Chat\ChatContextResolverInterface;
use Overblog\GraphQLBundle\Annotation\Arg;
use Overblog\GraphQLBundle\Annotation\Mutation;
use Overblog\GraphQLBundle\Annotation\Provider;
use Symfony\Component\Uid\Ulid;

#[Provider(targetMutationTypes: 'Mutation')]
class ChatParticipantMutationResolver
{

    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private ChatContextResolverInterface $chatContextResolver,
    ) {
    }


    #[Mutation]
    public function createNewChatMessage(ChatMessageCreationInput $input, ?string $clientMutationId = null): CQRSEntryCreationCommandResult
    {

        $participant = $this->getParticipantByChannelId($input->channelId);
        $command = new CreateChatMessage();
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
        name: 'input',
        type: 'ChatMessageUpdateInput!'
    )]
    #[Arg(
        name: 'clientMutationId',
        type: 'String'
    )]
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




    public function getParticipantByChannelId(Ulid $id): ChatParticipant
    {
        return  $this->chatContextResolver->resolveCurrentChatParticipantForChannelId($id);
    }
}
