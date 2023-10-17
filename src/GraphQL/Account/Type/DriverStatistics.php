<?php
namespace App\GraphQL\Account\Type;

use App\Entity\Account\Driver;
use App\Entity\Shipment\ShipmentBidStatus;
use App\Entity\Shipment\ShipmentOrderStatus;
use App\Repository\Account\DriverRepository;
use App\Repository\Shipment\ShipmentDriverBidRepository;
use App\Repository\Shipment\ShipmentOrderRepository;
use App\Util\Doctrine\QueryBuilderHelper;
use DateTimeInterface;
use Doctrine\ORM\QueryBuilder;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Bridge\Doctrine\Types\UlidType;

#[GQL\Type()]
class DriverStatistics
{

    // private DriverRepository $driverRepository;
    // private ShipmentOrderRepository $shipmentOrderRepository;
    // private ShipmentDriverBidRepository $shipmentDriverBidRepository;
    // private Driver $driver;

    private function __construct(
        private DriverRepository $driverRepository,
        private ShipmentOrderRepository $shipmentOrderRepository,
        private ShipmentDriverBidRepository $shipmentDriverBidRepository,
        private Driver $driver,
    ) {
    }


    public static function create(
        DriverRepository $driverRepository,
        ShipmentOrderRepository $shipmentOrderRepository,
        ShipmentDriverBidRepository $shipmentDriverBidRepository,
        Driver $driver,
    ) {
        return new static(
            $driverRepository,
            $shipmentOrderRepository,
            $shipmentDriverBidRepository,
            $driver,
        );
    }



    #[GQL\Field(name: 'conversionRate')]
    #[GQL\Arg(name: 'from', type: 'DateTime')]
    #[GQL\Arg(name: 'to', type: 'DateTime')]
    #[GQL\Arg(name: 'filter', type: 'String')]
    public function getConversionRate(
        ?DateTimeInterface $from = null,
        ?DateTimeInterface $to = null,
        ?string $filter = null,
    ): ?float {

        static::validateDateRange(
            from: $from,
            to: $to
        );

        $rate = 0.0;


        $bidCount = $this->getBidCount(
            from: $from,
            to: $to,
            filter: $filter,
        );

        $orderCount = $this->getOrderCount(
            from: $from,
            to: $to,
            filter: $filter,
            bidded: true,
        );

        if ($bidCount == 0) {
            return  null;   // No Bids yet
        }

        $rate = $orderCount / $bidCount;

        $percentage = $rate * 100;

        return $percentage;
    }



    #[GQL\Field(name: 'orderCount')]
    #[GQL\Arg(name: 'from', type: 'DateTime')]
    #[GQL\Arg(name: 'to', type: 'DateTime')]
    #[GQL\Arg(name: 'status', type: '[ShipmentOrderStatus!]')]
    #[GQL\Arg(name: 'bidded', type: 'Boolean')]
    #[GQL\Arg(name: 'filter', type: 'String')]
    public function getOrderCount(
        ?DateTimeInterface $from = null,
        ?DateTimeInterface $to = null,
        ?array $status = null,
        ?bool $bidded = null,
        ?string $filter = null,
    ): int {

        static::validateDateRange(
            from: $from,
            to: $to
        );

        $qb = $this->shipmentOrderRepository
            ->createQueryBuilder('_order');
        $this->applyDriver($qb, '_order');
        QueryBuilderHelper::applyCriteria($qb, $filter, '_order');

        if ($from) {
            $qb->andWhere($qb->expr()->gte('_order.createdAt', ':from'))
                ->setParameter('from', $from);
        }
        if ($to) {
            $qb->andWhere($qb->expr()->lte('_order.createdAt', ':to'))
                ->setParameter('to', $to);
        }

        if ($bidded !== null) {
            if ($bidded) {
                $qb->innerJoin('_order.bid', 'bid')
                    ->innerJoin('bid.driver', 'bid_driver')
                    ->andWhere('bid_driver.id = :bid_driver',)
                    ->setParameter('bid_driver', $this->driver->getId(), UlidType::NAME);
            } else {
                $qb->andWhere($qb->expr()->isNull('_order.bid'));
            }
        }

        if ($status) {
            $qb->andWhere($qb->expr()->in('_order.status', array_map(fn($st) => $st->value, $status)))
                // ->setParameter('status', $status)
                ;
        }

        $count = (int) $qb->select('COUNT(_order.id)')->getQuery()->getSingleScalarResult();
        return $count;
    }

    #[GQL\Field(name: 'bidCount')]
    #[GQL\Arg(name: 'from', type: 'DateTime')]
    #[GQL\Arg(name: 'to', type: 'DateTime')]
    #[GQL\Arg(name: 'status', type: '[ShipmentBidStatus!]')]
    #[GQL\Arg(name: 'filter', type: 'String')]
    public function getBidCount(
        ?DateTimeInterface $from = null,
        ?DateTimeInterface $to = null,
        ?array $status = null,
        ?string $filter = null,
    ): int {

        static::validateDateRange(
            from: $from,
            to: $to
        );

        $qb = $this->shipmentDriverBidRepository
            ->createQueryBuilder('bid');
        $this->applyDriver($qb, 'bid');

        QueryBuilderHelper::applyCriteria($qb, $filter, 'bid');

        if ($from) {
            $qb->andWhere($qb->expr()->gte('bid.createdAt', ':from'))
                ->setParameter('from', $from);
        }
        if ($to) {
            $qb->andWhere($qb->expr()->lte('bid.createdAt', ':to'))
                ->setParameter('to', $to);
        }

        if ($status) {
            $qb->andWhere($qb->expr()->in('bid.status', array_map(fn($st) => $st->value, $status)))
                // ->setParameter('status', $status)
                ;
        }

        $count = (int) $qb->select('COUNT(bid.id)')->getQuery()->getSingleScalarResult();
        return $count;
    }


    private static function validateDateRange(
        ?DateTimeInterface $from = null,
        ?DateTimeInterface $to = null,
    ): void {
        if ($from && $to) {
            if ($from->diff($to)->f > 0) {
                throw new UserError("from time should not exceed the to variable");
            }
        }
    }

    private function applyDriver(QueryBuilder $qb, string $name): void
    {
        $qb->innerJoin($name . '.driver', 'driver')
            ->andWhere('driver.id = :driver')
            ->setParameter('driver', $this->driver->getId(), UlidType::NAME);
    }

    // TODO: Apply
}
