<?php

namespace App\Controller\Payment;

use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentMethodController extends AbstractController
{

    public function __construct(
        private StripeClient $stripe,
    ) {
    }

    #[Route('/payment/payment/method', name: 'app_payment_payment_method')]
    public function index(): Response
    {
        return $this->render('payment/payment_method/index.html.twig', [
            'controller_name' => 'PaymentMethodController',
        ]);
    }


    public function create(Request $request): Response
    {

        $token = $_POST['stripeToken'];
        $customer_id = 'CUSTOMER_ID'; // Replace with the customer's ID from your database

        try {
            $paymentMethod = $this->stripe->paymentMethods->create([
                'type' => 'card',
                'card' => [
                    'number' => $_POST['card_number'],
                    'exp_month' => $_POST['exp_month'],
                    'exp_year' => $_POST['exp_year'],
                    'cvc' => $_POST['cvc'],
                ],
            ]);

            // Attach the Payment Method to the customer
            $paymentMethod->attach(['customer' => $customer_id]);

            // You can save the Payment Method ID for future transactions
            $paymentMethodId = $paymentMethod->id;

            // Redirect or display a success message
            header('Location: success.php');
        } catch (\Stripe\Exception\CardException $e) {
            // Handle card errors (e.g., invalid card number, expired card)
            echo 'Card error: ' . $e->getError()->message;
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Handle other Stripe API errors
            echo 'Stripe error: ' . $e->getMessage();
        }

        return $this->json([
            'status' => 'error',
        ]);
    }
}
