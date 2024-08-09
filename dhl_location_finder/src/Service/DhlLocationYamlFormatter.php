<?php

namespace Drupal\dhl_location_finder\Service;

use Symfony\Component\Yaml\Yaml;

class DhlLocationYamlFormatter {

  public function formatLocationsAsYaml(array $locations): string {
    $output = '';
    foreach ($locations as $location) {
      $yaml = Yaml::dump([
        'locationName' => $location['name'],
        'address' => [
          'countryCode' => $location['place']['address']['countryCode'],
          'postalCode' => $location['place']['address']['postalCode'],
          'addressLocality' => $location['place']['address']['addressLocality'],
          'streetAddress' => $location['place']['address']['streetAddress'],
        ],
        'openingHours' => $this->formatOpeningHours($location['openingHours']),
      ], 3);
      $output .= $yaml;
    }
    return $output;
  }

  private function formatOpeningHours(array $openingHours): array {
    $weekDaysHours = [];
    foreach ($openingHours as $hours) {
      $dayOfWeek = basename($hours['dayOfWeek']);
      $opens = $hours['opens'];
      $closes = $hours['closes'];
      $weekDaysHours[$dayOfWeek] = $opens . " - " . $closes;
    }
    return $weekDaysHours;
  }
}
