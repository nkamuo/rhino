<?php

namespace App\MessageHandler\Account;

use App\Entity\Account\Driver;
use App\Entity\Shipment\Assessment\Review;
use App\Message\Account\CalculateDriverRating;
use App\Repository\Account\DriverRepository;
use App\Repository\Shipment\Assessment\ReviewRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Uid\Ulid;

final class CalculateDriverRatingHandler
{


    public function __construct(
        private DriverRepository $driverRepository,
        private ReviewRepository $reviewRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[AsMessageHandler()]
    public function calculateDriverRating(CalculateDriverRating $command)
    {
        $driver = $this->getDriverById($command->getDriverId());
        if(null === $driver)
        return;

        $qb = $this->reviewRepository->createQueryBuilder('review');
        $qb
            ->innerJoin('review.shipmentOrder', 'shipmentOrder')
            ->innerJoin('shipmentOrder.driver', 'driver')
            ->andWhere('driver.id = :driver')
            ->setParameter('driver', $driver->getId(), UlidType::NAME);

        /** @var Collection|Review[] */
        $reviews = $qb->getQuery()->getResult();

        $total = 0;
        $count = 0;
        foreach ($reviews as $review) {
            $total += $review->getRating();
            $count++;
        }

        $rating = 0;
        if ($count == 0) {
        } else {
            $rating = $total / $count;
        }

        $summary = $driver->getReview();

        $summary
            ->setRating($rating)
            ->setCount($count);

        $this->entityManager->persist($driver);
        $this->entityManager->flush();
    }



    private function getDriverById(Ulid $id): ?Driver
    {
        return $this->driverRepository->find($id);
    }
}
