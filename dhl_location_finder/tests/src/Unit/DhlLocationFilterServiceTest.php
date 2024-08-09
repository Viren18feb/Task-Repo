<?php

namespace Drupal\Tests\dhl_location_finder\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\dhl_location_finder\Service\DhlLocationFilterService;

class DhlLocationFilterServiceTest extends UnitTestCase {

  public function testFilterLocations() {
    $service = new DhlLocationFilterService();

    $locations [] =
    [
      'place' =>
       [
        'address' =>
        [
          'addressLocality' => 'PRAGUE'
          ]
        ],

      'openingHours' => [
        'dayOfWeek' => ['http://schema.org/Saturday', 'http://schema.org/Sunday'],
      ],
    ];
   $locations [] = [
      'place' =>
       [
        'address' =>
        [
          'addressLocality' => 'Praha'
          ]
        ],

      'openingHours' => [
        'dayOfWeek' => ['http://schema.org/Saturday', 'http://schema.org/Sunday'],
      ],
    ];

    $filtered_locations = $service->filterLocations($locations);
    $this->assertCount(1, $filtered_locations);
  }
}
