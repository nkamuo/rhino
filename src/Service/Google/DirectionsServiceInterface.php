<?php
namespace App\Service\Google;
use App\Entity\Addressing\Address;
use App\Entity\Addressing\Routing\Route;
use Brick\Geo\LineString;



interface DirectionsServiceInterface
{
    
    /**
     * @param Address[] $locations
     * @return LineString[]
     */
  public function getDirections(array $locations): ?array;
  
  /**
  * @inheritDoc
  */
 public function getRoute(Address $origin, Address $destination): Route;

}