<?php
namespace App\Service\Google;

use App\Entity\Addressing\Address;
use App\Entity\Addressing\Routing\Route;
use App\Service\Identity\CodeGeneratorInterface;
use Brick\Geo\IO\WKBReader;
use Brick\Geo\IO\WKTReader;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

// \GraphQL\Error\Error::createLocatedError;

class DirectionsService implements DirectionsServiceInterface
{

  public const URL = 'https://maps.googleapis.com/maps/api/directions/json?';
  private string $url = self::URL;
  private string $apiKey;
  private HttpClientInterface $httpClient;

  private WKTReader $wktReader;

  public function __construct(
    HttpClientInterface $httpClient,
    private LoggerInterface $logger,
    private CodeGeneratorInterface $codeGenerator,
    string $apiKey,
    ?string $url = self::URL,
  ) {
    $this->apiKey = $apiKey;
    $this->httpClient = $httpClient;
    $this->url = $url;
    $this->wktReader = new WKTReader();
  }

  /**
   * @inheritDoc
   */
  public function getDirections(array $locations): ?array
  {
    $params = [
      'key' => $this->apiKey,
      'origin' => $this->getLocationData($locations[0]),
      'destination' => $this->getLocationData($locations[count($locations) - 1]),
      'waypoints' => $this->getWaypoints($locations),
      'mode' => 'driving',
      // 'optimizeWaypoints' => true,
    ];

    $response = $this->httpClient->request('GET', $this->url . http_build_query($params));

    if ($response->getStatusCode() !== 200) {
      throw new \RuntimeException('Failed to retrieve directions from Google Maps API.');
    }

    $content = json_decode($response->getContent(), true);

    if ($content['status'] !== 'OK') {
      return null;
    }

    $routes = $content['routes'][0];


    // $this->logger->debug('[DATA]: '. json_encode($routes));
    // return [];

    $polylines = array_map(function ($leg) {
      $points = [];
      foreach ($leg['steps'] as $step) {
        $encodedPolyline = $step['polyline']['points'];
        $originalPoints = /*WkbPolyline::encode*/(\Polyline::decode($encodedPolyline));
        $newPointPairs = \Polyline::pair($originalPoints);
        $points = [...$points, ...$newPointPairs];
      }
      return $points;
    }, $routes['legs']);


    $linestrings = [];

    foreach ($polylines as $polyline) {
      $points = new ArrayCollection($polyline);
      $reducedPoints = $points->map(fn(array $point) => implode(' ', $point))->toArray();
      $linestring = "LINESTRING(" . implode(' , ', $reducedPoints) . ')';
      $linestrings[] = $this->wktReader->read($linestring);
    }

    return $linestrings;
  }






   /**
   * @inheritDoc
   */
  public function getRoute(Address $origin, Address $destination): Route
  {
    $params = [
      'key' => $this->apiKey,
      'origin' => $this->getLocationData($origin),
      'destination' => $this->getLocationData($destination),
      // 'waypoints' => $this->getWaypoints($locations),
      'mode' => 'driving',
      // 'optimizeWaypoints' => true, //DEFINITLY NOT TRUE
    ];

    $response = $this->httpClient->request('GET', $this->url . http_build_query($params));

    if ($response->getStatusCode() !== 200) {
      throw new \RuntimeException('Failed to retrieve directions from Google Maps API.');
    }

    $content = json_decode($response->getContent(), true);

    if ($content['status'] !== 'OK') {
      throw new \LogicException("Route Request failed");
      // return null;
    }

    $groute = $content['routes'][0];


    // $this->logger->debug('[DATA]: '. json_encode($routes));
    // return [];

    $points = [];
    $distance = 0;
    $duration = 0;
    // $polylines = [];
    
    foreach($groute['legs'] as $leg) {
      foreach ($leg['steps'] as $step) {
        $distance += $step['distance']['value'];
        $duration += $step['duration']['value'];
        $encodedPolyline = $step['polyline']['points'];
        $originalPoints = /*WkbPolyline::encode*/(\Polyline::decode($encodedPolyline));
        $newPointPairs = \Polyline::pair($originalPoints);
        $points = [...$points, ...$newPointPairs];
      }
      // $points;
    }


    
    // $polyline = array_reduce($polylines, fn(array $prev, array $next) => ([...$prev,...$next]), []);

      $points = new ArrayCollection($points);
      $reducedPoints = $points->map(fn(array $point) => implode(' ', $point))->toArray();
      $linestring = "LINESTRING(" . implode(' , ', $reducedPoints) . ')';
      $polyline = $this->wktReader->read($linestring);


      $route = new Route();

      $route
        ->setCode($this->codeGenerator->generateCode())
        ->setDistance($distance)
        ->setDuration($duration)
        ->setStartPlaceId($origin->getGoogleId())
        ->setEndPlaceId($destination->getGoogleId())
        ->setStartPoint($origin->getCoordinate()?->toPoint())
        ->setEndPoint($destination->getCoordinate()?->toPoint())
        ->setPolyline($polyline)
        ;
    

    return $route;
  }

  /**
   * @param Address[] $locations
   */
  private function getWaypoints(array $locations): string
  {
    $waypoints = [];

    for ($i = 1; $i < count($locations) - 1; $i++) {
      $location = $locations[$i];
      $waypoints[] = $this->getLocationData($location);
    }

    return implode('|', $waypoints);
  }


  private function getLocationData(Address $address): string
  {
    $data = null;

    if (($placeId = $address->getGoogleId())) {
      $data = 'place_id:' . $placeId;
    } elseif (($coordinate = $address->getCoordinate())) {
      $data = "{$coordinate->getLatitude()},{$coordinate->getLongitude()}";
    } else {
      $data = 'address:' . $address->getFullyFormattedRepresentaion();
    }
    return $data;
  }

}

























/**
 * Well Known Binary Polyline example file
 *
 * PHP Version 5.3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Examples
 * @package   WkbPolyline
 * @author    E. McConville <emcconville@emcconville.com>
 * @copyright 2014 emcconville
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL v3
 * @link      https://github.com/emcconville/google-map-polyline-encoding-tool
 * @since     v1.2.4
 */

/**
 * Well Known Binary Polyline example
 *
 * An example class to convert well-known binary files to Google encoded strings
 *
 * @category  Examples
 * @package   WkbPolyline
 * @author    E. McConville <emcconville@emcconville.com>
 * @copyright 2014 emcconville
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL v3
 * @link      https://github.com/emcconville/google-map-polyline-encoding-tool
 * @since     v1.2.4
 * @internal Used by the DirectionsService Only
 */
class WkbPolyline extends \Polyline
{
  const ENDIAN_BIG = 0;
  const ENDIAN_LITTLE = 1;

  protected $fd;
  protected $cursor;
  protected $endianness;

  /**
   * Parse binary file & converts WKB to google encoded string.
   *
   * @param string $filename - The path to the binary file to be encoded.
   *
   * @return string - Encoded string
   */
  public function encodeFromFile($filename)
  {
    $this->fd = fopen($filename, 'rb');
    $points = $this->parseWkb();
    fclose($this->fd);

    return parent::encode($points);
  }

  /**
   * Encoded WKB from blob
   *
   * This method will copy the given blob to memory descriptor. There's better
   * ways to do this.
   *
   * @param string $blob - Binary safe string
   *
   * @return string
   */
  public function encodeFromBlob($blob)
  {
    $this->fd = fopen('php://memory', 'wb');
    fwrite($this->fd, $blob);
    fseek($this->fd, 0);
    $points = $this->parseWkb();
    fclose($this->fd);

    return parent::encode($points);
  }

  /**
   * Extract points from well-known binary
   *
   * @return array - Points found in binary
   */
  protected function parseWkb()
  {
    assert(is_resource($this->fd), "Not a resource");
    // Read first byte to determine endianness.
    $this->endianness = $this->readByte();
    // Get unsigned integer, and convert to vector type.
    $header = $this->readU32() % 1000;
    // This example will only support `Polygon` shapes
    assert($header == 3, "This example only covers polyline shapes");

    $points = array();

    // Iterate over circles
    for ($i = 0, $l = $this->readU32(); $i < $l; $i++) {
      // Iterate over points
      for ($j = 0, $p = $this->readU32(); $j < $p; $j++) {
        $points[] = $this->readDouble(); // latitude
        $points[] = $this->readDouble(); // longitude
      }
    }
    return $points;
  }

  /**
   * Read 8 bytes and cast to double. Respects file endianness.
   *
   * @return double
   */
  protected function readDouble()
  {
    $data = $this->chunk(8);
    if ($this->endianness == self::ENDIAN_BIG) {
      $data = strrev($data);
    }
    $double = unpack('ddouble', $data);
    return $double['double'];
  }

  /**
   * Read 4 bytes and cast to unsigned integer. Respects file endianness.
   *
   * @return integer
   */
  protected function readU32()
  {
    $format = $this->endianness ? 'Vlong' : 'Nlong';
    $unsignedLong = unpack($format, $this->chunk(4));
    return $unsignedLong['long'];
  }

  /**
   * Read single byte from file descriptor.
   *
   * @return integer - Order of byte.
   */
  protected function readByte()
  {
    return ord($this->chunk());
  }

  /**
   * Pulls data directly from file descriptor by given length.
   *
   * @param integer $size - Default 1
   *
   * @return string - Binary safe string.
   */
  protected function chunk($size = 1)
  {
    $this->cursor += $size;
    return fread($this->fd, $size);
  }

}