<?php

namespace App\Message\Account;

use App\Entity\Account\Driver;
use Symfony\Component\Uid\Ulid;

final class CalculateDriverRating
{
    private Ulid $driverId;
    public function __construct(Driver $driver)
    {
        $this->driverId = $driver->getId();
    }

    public function getDriverId(): Ulid
    {
        return $this->driverId;
    }
}
