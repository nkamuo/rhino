<?php

namespace App\DataFixtures\Shipment\Assessment;

use App\Entity\Shipment\Assessment\AssessmentParameter;
use App\Repository\Shipment\Assessment\AssessmentParameterRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// src/DataFixtures/UserFixtures.php
// ...
class AssessmentParameterFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';


    public function __construct(
        private AssessmentParameterRepository $assessmentParameterRepository,
    ) {
    }

    public function load(ObjectManager $manager)
    {

        $data = [
            [
                'code' => 'handling',
                'title' => 'Handling',
                'subtitle' => 'How well did this driver handle your shipment?',
                'icon' => 'mdi-driver'
            ],
            [
                'code' => 'speed',
                'title' => 'Fast Delivery',
                'subtitle' => 'How satisfied are you with the drivers\'s time to delivery',
                'icon' => 'mdi-speed'
            ],
            [
                'code' => 'communication',
                'title' => 'Communication',
                'subtitle' => 'How much necesarry information was passed to you by the driver',
                'icon' => 'mdi-communication'
            ],
        ];


        foreach ($data as $entry) {
            $parameter = $this->assessmentParameterRepository->findOneBy(['code' => $entry['code']]);

            if (!$parameter) {
                $parameter = new AssessmentParameter();
            }
            $parameter
                ->setCode($entry['code'])
                ->setTitle($entry['title'])
                ->setSubtitle($entry['subtitle'])
                ->setIcon($entry['icon']);
            $manager->persist($parameter);
        }

        $manager->flush();

        // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
        // $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);
    }
}
