<?php

namespace Drupal\dhl_location_finder\Service;

class DhlLocationFilterService {

  public function filterLocations(array $locations): array {
    return array_filter($locations, function($location) {
      return $this->worksOnWeekends($location) && $this->hasOddAddressNumber($location['place']['address']['addressLocality']);
    });
  }

  private function worksOnWeekends(array $location): bool {

    $worksOnSaturday = false;
    $worksOnSunday = false;

    // Check the opening hours for each day of the location.
    foreach ($location['openingHours'] as $hours) {
      if ($hours['dayOfWeek'] === 'http://schema.org/Saturday') {
        $worksOnSaturday = true;
      }
      if ($hours['dayOfWeek'] === 'http://schema.org/Sunday') {
        $worksOnSunday = true;
      }
    }

    if($worksOnSaturday || $worksOnSunday){
      $woekOnWeekends = TRUE;
    }else{
      $woekOnWeekends = FALSE;
    }

    return $woekOnWeekends;
  }

  private function hasOddAddressNumber(string $address): bool {
    return strlen($address) % 2 !== 0 ? TRUE : FALSE;
  }
}
