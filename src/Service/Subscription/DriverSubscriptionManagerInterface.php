<?php

namespace App\Service\Subscription;

use App\Entity\Account\Driver;
use Doctrine\Common\Collections\Collection;
use Stripe\Subscription;

interface DriverSubscriptionManagerInterface
{

    public function refreshDriverSubscription(Driver $driver): void;

    public function retriveDriverSubscription(Driver $driver): Subscription;

    public function retriveDriverProductSubscriptions(Driver $driver, string $product, ?string $status = null, ?int $limit = null): Collection;
}
