<?php

namespace App\GraphQL\Chat\Resolver;

use App\CQRS\Command\Result\CQRSEntryCreationCommandResult;
use App\CQRS\Command\Result\CQRSEntryUpdateCommandResult;
use App\CQRS\CommandBusInterface;
use App\CQRS\QueryBusInterface;
use App\GraphQL\Chat\Input\Channel\ChatChannelCreationInput;
use App\GraphQL\Chat\Input\Channel\ChatChannelUpdateInput;
use App\Message\Chat\Channel\CreateChannel;
use App\Message\Chat\Channel\DeleteChannel;
use App\Message\Chat\Channel\UpdateChannel;
use App\Repository\Addressing\AddressRepository;
use App\Service\Chat\ChatContextResolverInterface;
use App\Service\Transport\Shipment\Billing\ShipmentAssetRewardBuilderInterface;
use App\Service\Transport\Shipment\Chat\ShipmentChatContextInterface;
use Overblog\GraphQLBundle\Annotation\Arg;
use Overblog\GraphQLBundle\Annotation\Mutation;
use Overblog\GraphQLBundle\Annotation\Provider;
use Symfony\Component\Uid\Ulid;

#[Provider(targetMutationTypes: 'Mutation')]
class ChatChannelMutationResolver
{

    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private AddressRepository $addressRepository,
        private ChatContextResolverInterface $chatContextResolver,
    ) {
    }


    #[Mutation]
    public function createNewChatChannel(ChatChannelCreationInput $input, ?string $clientMutationId = null): CQRSEntryCreationCommandResult
    {

        $command = new CreateChannel();
        $command
            // ->setTitlte($input->title)
        ;
        return $this->commandBus->getMutationContext($clientMutationId)->dispatch($command);
    }


    #[Mutation()]
    #[Arg(name: 'clientId',  type: 'Ulid!')]
    #[Arg(name: 'input', type: 'ChatChannelUpdateInput!')]
    #[Arg(name: 'clientMutationId', type: 'String')]
    public function updateChatChannel(Ulid $id, ChatChannelUpdateInput $input, ?string $clientMutationId = null): CQRSEntryUpdateCommandResult
    {

        $command = new UpdateChannel($id);
        $command
            // ->setTitle($input->title)
        ;
        return $this->commandBus->getMutationContext($clientMutationId)->dispatch($command);
    }


    #[Mutation()]
    #[Arg(name: 'clientId', type: 'Ulid!'),]
    #[Arg(name: 'clientMutationId', type: 'String')]
    public function deleteChatChannel(Ulid $id, ?string $clientMutationId = null): ?bool
    {
        $command = new DeleteChannel($id);
        $this->commandBus->getMutationContext($clientMutationId)->dispatch($command);
        return null;
    }
}
