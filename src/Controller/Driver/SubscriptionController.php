<?php

namespace App\Controller\Driver;

use App\Entity\Account\User;
use Overblog\GraphQLBundle\Security\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{


    public function __construct(
        private Security $security,
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


    #[Route('/driver/subscription', name: 'app_driver_subscription')]
    public function index(): Response
    {

        $stripeKey = $this->getParameter('stripe.secret_key');

        \Stripe\Stripe::setApiKey($stripeKey);

        $email = 'callistusnkamuo@gmail.com';
        $stripeToken   = '';

        $customer = \Stripe\Customer::create([
            'email' => $email,
            // 'source' => $stripeToken,
        ]);

        // $products = \Stripe\Product::all();


        // return $this->json([
        //     'products' => $products,
        // ]);

        $subscription = \Stripe\Subscription::create([
            'customer' => $customer->id,
            'items' => [
                [
                    'price' => 'price_1O0tVCFeXzz6dbsSLFnEfwSw', //'YOUR_PRODUCT_PRICE_ID'
                ]
            ],
        ]);

        return $this->render('driver/subscription/index.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }
}
