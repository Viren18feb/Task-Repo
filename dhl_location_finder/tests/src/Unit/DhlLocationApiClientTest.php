<?php
namespace Drupal\Tests\dhl_location_finder\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\dhl_location_finder\Service\DhlLocationApiClient;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class DhlLocationApiClientTest extends UnitTestCase {

  public function testFetchLocations() {
    $http_client = $this->createMock(ClientInterface::class);
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getBody')->willReturn(json_encode([
      'locations' => [
        ['locationName' => 'Prague', 'countryCode' => 'CZ', 'postalCode' => '11000']
      ]
    ]));
    $http_client->method('request')->willReturn($response);

    $api_client = new DhlLocationApiClient($http_client);
    $locations = $api_client->fetchLocations('CZ', 'Prague', '11000');
    $this->assertCount(1, $locations);
  }
}
