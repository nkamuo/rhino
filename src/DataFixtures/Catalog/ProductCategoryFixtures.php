<?php

namespace App\DataFixtures\Catalog;

use App\Entity\Catalog\ProductCategory;
use App\Entity\Shipment\Assessment\AssessmentParameter;
use App\Repository\Catalog\ProductCategoryRepository;
use App\Repository\Catalog\ProductRepository;
use App\Repository\Shipment\Assessment\AssessmentParameterRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// src/DataFixtures/UserFixtures.php
// ...
class ProductCategoryFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';


    public function __construct(
        private ProductCategoryRepository $productCategoryRepository,
    ) {
    }

    public function load(ObjectManager $manager)
    {

        $data = [
            [
                'code' => 'CONSUMER_ELECTRONICS',
                'title' => 'Comsumer Electrinics',
                'shortName' => 'Consumer ELcs',
                'longName' => 'Consumer Electronics and Appiance',
                'icon' => 'mdi-driver',
            ],
            [
                'code' => 'BUILDING_MATERIALS',
                'title' => 'Building Materials',
                'shortName' => 'Building Mats',
                'longName' => 'Housing and Building Materials',
                'icon' => 'mdi-driver'
            ],
            [
                'code' => 'FOOD_AND_GROCERIES',
                'title' => 'Food and Groceries',
                'shortName' => 'Groceries',
                'longName' => 'Housing and Building Materials',
                'icon' => 'mdi-driver'
            ],
        ];


        foreach ($data as $entry) {
            $category = $this->productCategoryRepository->findOneBy(['code' => $entry['code']]);
            if (!$category){
                $category = new ProductCategory();
            }
            $category
                ->setCode($entry['code'])
                ->setName($entry['title'])
                ->setShortName($entry['shortName'])
                // ->setIcon($entry['icon'])
            ;
            $manager->persist($category);
        }

        $manager->flush();

        // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
        // $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);
    }
}
