<?php

namespace Drupal\dhl_location_finder\Service;

use GuzzleHttp\ClientInterface;

class DhlLocationApiClient {

  protected $httpClient;

  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  public function fetchLocations($country, $city, $postal_code) {
    $response = $this->httpClient->request('GET', 'https://api.dhl.com/location-finder/v1/find-by-address', [
      'headers' => [
        'DHL-API-Key' => 'B8iD8CFpa76AXAAh3g29a8EgeLGar1Wi',
      ],
      'query' => [
        'countryCode' => $country,
        'addressLocality' => $city,
        'postalCode' => $postal_code,
      ],
    ]);

    return json_decode($response->getBody(), TRUE)['locations'];
  }
}
