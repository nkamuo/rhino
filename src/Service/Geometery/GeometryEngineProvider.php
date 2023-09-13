<?php
namespace App\Service\Geometery;
use Brick\Geo\Engine\GeometryEngine;
use Brick\Geo\Engine\PDOEngine;
use Doctrine\ORM\EntityManagerInterface;


class GeometryEngineProvider{

    public function __construct(private EntityManagerInterface $entityManager){

    }


    public function getGeometryEngine(): GeometryEngine{
        $pdoConnection = $this->entityManager->getConnection()->getNativeConnection();
        $engine = new PDOEngine($pdoConnection);
        return $engine;
    }

}