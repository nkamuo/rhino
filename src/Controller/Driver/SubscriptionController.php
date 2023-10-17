<?php

namespace App\Controller\Driver;

use App\Entity\Account\Driver;
use App\Entity\Account\User;
use App\Service\Subscription\DriverSubscriptionManagerInterface;
use App\Service\Subscription\Exception\SubscriptionException;
use Overblog\GraphQLBundle\Security\Security;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/driver/subscriptions', name: 'app_driver_subscription')]
class SubscriptionController extends AbstractController
{


    public function __construct(
        private Security $security,
        private StripeClient $stripe,
        private ParameterBagInterface $parameterBag,
        private DriverSubscriptionManagerInterface $driverSubscriptionManager,
    ) {
    }

    public function checkSubscription()
    {

        $user = $this->getUser();

        if (!($user instanceof User)) {
            throw $this->createAccessDeniedException();
        }

        $customerId = $user->getStripeCustomerId();

        $subscriptions = \Stripe\Subscription::all([
            'customer' => $customerId,
            'status' => 'active',
        ]);

        if (count($subscriptions->data) > 0) {
            // User has an active subscription, allow access
        } else {
            // User does not have an active subscription, restrict access
        }
    }


    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {

        $driver = $this->getDriver();
        $product = $this->getParameter('driver.subscription.stripe_product_id');

        $subscriptions = $this->driverSubscriptionManager->retriveDriverProductSubscriptions(
            driver: $driver,
            product: $product
        );


        return $this->json([
            'status' => 'success',
            'data' => $subscriptions,
        ]);
    }



    #[Route('/current', name: 'current', methods: ['GET'])]
    public function current(): Response
    {
        try {
            $driver = $this->getDriver();
            $subscription = $this->driverSubscriptionManager->retriveDriverSubscription(
                driver: $driver,
            );
            return $this->json([
                'status' => 'success',
                'data' => $subscription,
            ]);
        } catch (SubscriptionException $e) {
            return $this->json([
                'status' => 'error',
                'data' => null,
            ]);
        }
    }



    #[Route('/subscribe', name: 'subscribe', methods: ['POST'])]
    public function subscribe(): Response
    {


        $user = $this->getAppUser();
        $priceId = $this->parameterBag->get('driver.subscription.stripe_price_id');
        $session = $this->stripe->checkout->sessions->create([
            //   'payment_method_types' => ['card'],
            'customer' => $user->getStripeCustomerId(),
            'payment_method_types' => ['sofort'],

            // or you can take multiple payment methods with
            // 'payment_method_types' => ['card', 'sofort', ...]
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => 'https://example.com/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'https://example.com/cancel',
        ]);


        return $this->json([
            'status' => 'success',
            'session' => [
                'id' => $session->id,
                'url' => $session->url,
            ],
        ]);

        // return $this->redirect($session->url);
        // 303 redirect to $session->url
    }



    #[Route('/{id}/cancel', name: 'cancel', methods: ['POST'])]
    public function cancel(string $id): Response
    {
        $this->stripe->subscriptions->cancel($id);
        return $this->json([
            'status' => 'success',
        ]);
    }



    private function getAppUser(): User
    {
        $user = $this->getUser();
        if (!($user instanceof User)) {
            throw $this->createAccessDeniedException();
        }
        return $user;
    }


    private function getDriver(): Driver
    {
        $user = $this->getAppUser();
        $driver = $user->getDriver();
        if (null == $driver) {
            throw $this->createAccessDeniedException();
        }
        return $driver;
    }
}
