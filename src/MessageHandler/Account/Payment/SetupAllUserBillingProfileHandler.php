<?php

namespace App\MessageHandler\Account\Payment;

use App\CQRS\CommandBusInterface;
use App\Message\Account\Payment\SetupAllUserBillingProfile;
use App\Message\Account\Payment\SetupUserStipeProfile;
use App\Repository\Account\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SetupAllUserBillingProfileHandler
{

    public function __construct(
        private UserRepository $userRepository,
        private CommandBusInterface $commandBus,
    ) {
    }
    #[AsMessageHandler()]
    public function setupAllBillingProfile(SetupAllUserBillingProfile $message)
    {
        $users = $this->userRepository
            ->createQueryBuilder('user')
            ->getQuery()
            ->getResult();

        foreach ($users as $user) {
            $this->commandBus->dispatch(
                new SetupUserStipeProfile(
                    userId: $user->getId(),
                )
            );
        }
    }
}
