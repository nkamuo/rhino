<?php

namespace App\Message\Account\Payment;

use Symfony\Component\Uid\Ulid;

final class SetupUserStipeProfile
{

    public function __construct(
        private Ulid $userId
    ) {
    }


    public function getUserId(): Ulid{
        return $this->userId;
    }
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

    //     private $name;

    //     public function __construct(string $name)
    //     {
    //         $this->name = $name;
    //     }

    //    public function getName(): string
    //    {
    //        return $this->name;
    //    }
}
