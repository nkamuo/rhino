<?php

namespace App\DataFixtures\Fleet;

use App\Entity\Vehicle\VehicleType;
use App\Entity\Shipment\Assessment\AssessmentParameter;
use App\Repository\Vehicle\VehicleTypeRepository;
use App\Repository\Catalog\ProductRepository;
use App\Repository\Shipment\Assessment\AssessmentParameterRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// src/DataFixtures/UserFixtures.php
// ...
class VehicleTypeFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';


    public function __construct(
        private VehicleTypeRepository $vehicleTypeRepository,
    ) {
    }

    public function load(ObjectManager $manager)
    {

        $data = [
            [
                'code' => 'DRY_VAN',
                'title' => 'Dry Van Trailer',
                'shortName' => 'DryVan',
                'longName' => 'Consumer Electronics and Appiance',
                'icon' => 'mdi-driver',
            ],
            [
                'code' => 'FLAT_BED',
                'title' => 'Flat Bed Trailer',
                'shortName' => 'Flatbed',
                'longName' => 'Housing and Building Materials',
                'icon' => 'mdi-driver'
            ],
            // [
            //     'code' => 'FOOD_AND_GROCERIES',
            //     'title' => 'Food and Groceries',
            //     'shortName' => 'Groceries',
            //     'longName' => 'Housing and Building Materials',
            //     'icon' => 'mdi-driver'
            // ],
        ];


        foreach ($data as $entry) {
            $vehicleType = $this->vehicleTypeRepository->findOneBy(['code' => $entry['code']]);
            if (!$vehicleType){
                $vehicleType = new VehicleType();
            }
            $vehicleType
                ->setCode($entry['code'])
                ->setName($entry['title'])
                ->setShortName($entry['shortName'])
                // ->setClientNote()
                // ->setIcon($entry['icon'])
            ;
            $manager->persist($vehicleType);
        }

        $manager->flush();

        // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
        // $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);
    }
}
