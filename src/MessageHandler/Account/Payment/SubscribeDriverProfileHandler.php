<?php

namespace App\MessageHandler\Account\Payment;

use App\Entity\Account\Driver;
use App\Message\Account\Payment\SubscribeDriverProfile;
use App\Repository\Account\UserRepository;
use App\Repository\Account\DriverRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Uid\Ulid;

final class SubscribeDriverProfileHandler
{

    public function __construct(
        private DriverRepository $userRepository,
        private DriverRepository $driverRepository,
        private ParameterBagInterface $parameterBag,
    ) {
    }

    #[AsMessageHandler]
    public function subscribeDriver(SubscribeDriverProfile $message)
    {
        $driver = $this->getDriver($message->getDriverId());
        $user = $driver->getUserAccount();

        $priceId = $this->parameterBag->get('driver.subscription.stripe_price_id');
        $stripeKey = $this->parameterBag->get('stripe.secret_key');
        \Stripe\Stripe::setApiKey($stripeKey);

        $customerId = $user->getStripeCustomerId();

        if (!$customerId) {
            //TODO: Dispatch SetupUserStripeProfile and let this command retry
            throw new UnrecoverableMessageHandlingException("There is no stripe customer ID found for user {$user->getFullName()}");
        }

        $customer = \Stripe\Customer::retrieve($customerId);

        $subscription = \Stripe\Subscription::create([
            'customer' => $customer->id,
            'items' => [
                [
                    'price' => $priceId, //'YOUR_PRODUCT_PRICE_ID'
                ]
            ],
        ]);
    }



    private function getDriver(Ulid $id): Driver
    {
        $driver = $this->driverRepository->find($id);
        if (null == $driver) {
            throw new UnrecoverableMessageHandlingException("Could not find driver with [id:{$id}]");
        }
        return $driver;
    }
}
