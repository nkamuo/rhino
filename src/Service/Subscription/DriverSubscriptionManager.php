<?php

namespace App\Service\Subscription;

use App\Entity\Account\Driver;
use App\Entity\Account\DriverSubscription;
use App\Service\Subscription\Exception\SubscriptionException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Stripe\Subscription;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DriverSubscriptionManager implements DriverSubscriptionManagerInterface
{

    public function __construct(
        private StripeClient $stripe,
        private ParameterBagInterface $parameterBag,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function refreshDriverSubscription(Driver $driver): void
    {
        // {
        //     //TODO: ALTERNATIVE, IF WE USE  PAYMENT METHODS, WE CAN ISSUE A COMMMAND TO CREATE THE SUBSCRIPTION FROM HERE.
        // }

        $driverSub = $driver->getSubscription() ?? $driver->setSubscription(new DriverSubscription())->getSubscription();
        $stripSub = $this->retriveDriverSubscription($driver);

        $driverSub->setStripeSubscriptionId($stripSub->id);

        $this->entityManager->persist($driver);
        $this->entityManager->flush();
    }

    public function retriveDriverSubscription(Driver $driver): Subscription
    {

        $user = $driver->getUserAccount();
        $sub = $driver->getSubscription();
        $subId = $sub?->getStripeSubscriptionId();
        $productId = $this->parameterBag->get('driver.subscription.stripe_product_id');
        $stripeCustomerId = $user->getStripeCustomerId();


        $subscriptions = $this->stripe->subscriptions->all([
            'customer' => $stripeCustomerId,
            // 'status' => 'active', // Optionally, you can filter by subscription status
        ]);

        $subscriptionId = null;

        // Loop through the subscriptions to find the one for the given product
        foreach ($subscriptions->data as $subscription) {
            foreach ($subscription->items->data as $item) {
                if ($item->price->product === $productId) {
                    // Found the subscription for the given product
                    $subscriptionId = $subscription->id;
                    // Do something with the subscription ID
                    break 2;
                }
            }
        }

        if (!$subscriptionId) {
            $message = sprintf(
                "Driver \"%s\" is currently not subscribed and has no stripe subscription ID",
                $user->getFullName()
            );
            throw new SubscriptionException($message);
        }

        $subscription = $this->stripe->subscriptions->retrieve($subscriptionId);
        return $subscription;
    }

    public function retriveDriverProductSubscriptions(Driver $driver, string $product, ?string $status = null, ?int $limit = null): Collection
    {

        $user = $driver->getUserAccount();
        $stripeCustomerId = $user->getStripeCustomerId();


        $result = $this->stripe->subscriptions->all([
            'customer' => $stripeCustomerId,
            ...($status ? ['status' => $status] : []),
            ...($limit ? ['limit' => $limit] : []),
            // 'status' => 'active', // Optionally, you can filter by subscription status
        ]);

        $subscriptions = new ArrayCollection();

        foreach ($result->data as $subscription) {
            foreach ($subscription->items->data as $item) {
                if ($item->price->product === $product) {
                    $subscriptions->add($subscription);
                }
            }
        }
        return $subscriptions;
    }
}
