<?php

namespace App\MessageHandler\Account\Payment;

use App\Entity\Account\User;
use App\Message\Account\Payment\SetupUserStipeProfile;
use App\Repository\Account\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Uid\Ulid;

final class SetupUserStipeProfileHandler
{

    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface $parameterBag,
    ) {
    }


    #[AsMessageHandler()]
    public function setupProfile(SetupUserStipeProfile $message)
    {

        
        $stripeKey = $this->parameterBag->get('stripe.secret_key');
        \Stripe\Stripe::setApiKey($stripeKey);

        $user = $this->getUser($message->getUserId());

        if ($customerId = $user->getStripeCustomerId()) {
            try {
                $customer = \Stripe\Customer::retrieve($customerId);            //TODO:  if($message->validateExisting)
                return;
            } catch (\Throwable $e) {
            }
        }

        try {
            $customer = \Stripe\Customer::create([
                'name' => $user->getFullName(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(),

                // 'source' => $stripeToken,
            ]);

            $user->setStripeCustomerId($customer->id);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            throw $e;
        }
    }


    private function getUser(Ulid $id): User
    {
        $user = $this->userRepository->find($id);
        if (null == $user) {
            throw new UnrecoverableMessageHandlingException("Could not find user with [id:{$id}]");
        }
        return $user;
    }
}
