<?php

namespace App\GraphQL\Account\Resolver;


use App\Entity\Account\Driver;
use App\Entity\Account\User;
use App\Entity\Document\DriverLicense;
use App\GraphQL\Account\Input\DriverLicenseInput;
use App\GraphQL\Account\Input\DriverUpdateInput;
use App\GraphQL\Account\Type\DriverStatistics;
use App\Repository\Account\DriverRepository;
use App\Repository\Shipment\ShipmentDriverBidRepository;
use App\Repository\Shipment\ShipmentOrderRepository;
use App\Service\Subscription\DriverSubscriptionManagerInterface;
use App\Service\Subscription\Exception\SubscriptionException;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Annotation\Arg;
use Overblog\GraphQLBundle\Annotation\Mutation;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Error\UserError;
use Stripe\Subscription;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;


#[GQL\Provider(
    targetQueryTypes: ['DriverQuery'],
    targetMutationTypes: ['DriverMutation'],
)]
class DriverSubscriptionResolver
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriverRepository $driverRepository,
        private DriverSubscriptionManagerInterface $driverSubscriptionManager,
        private Security $security,
    ) {
    }


    #[Query(name: "get_current_driver_subscription", type: 'Json')]
    public function getCurrentDriverSubscription(): ?Subscription
    {
        try {
            $driver = $this->getDriver();
            $subscription = $this->driverSubscriptionManager->retriveDriverSubscription($driver);
            return $subscription;
        } catch (SubscriptionException $e) {
            return null;
        }
    }


    private function getDriver(?Ulid $id = null): Driver
    {
        if (null == $id) {
            $driver = $this->fetchCurrentDriver();
        } else {
            $driver =  $this->driverRepository->find($id);
            if (null == $driver) {
                throw new UserError("Could not find driver with [id:{$id}]");
            }
        }
        return $driver;
    }



    private function fetchCurrentDriver(): Driver
    {
        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError(
                message: "Cannot find current user"
            );
        }
        $driver = $user->getDriver();

        if (null == $driver) {
            throw new UserError("Could not resolve the current driver account");
        }
        return $driver;
    }
}
