<?php
namespace App\Service\Chat;

use App\Entity\Chat\ChatChannel;
use App\Entity\Chat\ChatParticipant;
use App\Entity\Chat\ChatUserParticipant;
use App\Service\Chat\Exception\ChatChannelParticipanResolutionException;
use Ramsey\Collection\Collection;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Uid\Ulid;



#[AutoconfigureTag(ChatContextResolverInterface::SERVICE_TAG_NAME)]
class ChainChatContextResolver implements ChatContextResolverInterface
{


    /**
     * @var iterable<ChatContextResolverInterface>
     */
    private iterable $resolvers = [];

    public function __construct(
        // the attribute must be applied directly to the argument to autowire
        #[TaggedIterator('chat.context.resolver', exclude: [self::class])]
        iterable $contextResolvers = []
        
    ) {
        // $_contextResolvers = new Collection(ChatContextResolverInterface::class,$contextResolvers);
        // $this->resolvers = $_contextResolvers
        //     ->filter(fn(ChatContextResolverInterface $resolver) => $resolver !== $this)
        //     ->toArray();

        $this->resolvers = $contextResolvers;
    }
    public function resolveCurrentChatParticipantForChannelId(Ulid $channelId): ChatParticipant
    {
       foreach($this->resolvers as $resolver){
        try{
            if($participant = $resolver->resolveCurrentChatParticipantForChannelId($channelId))
                return $participant;
        }
        catch(ChatChannelParticipanResolutionException $e){
            continue;
        }
       }

       throw new \BadFunctionCallException("Could not find a matching participant for the current reques");
;    }


    public function resolveCurrentChatParticipantForChannel(ChatChannel $channel): ChatUserParticipant
    {
        return $this->resolveCurrentChatParticipantForChannelId($channel->getId());
    }
}