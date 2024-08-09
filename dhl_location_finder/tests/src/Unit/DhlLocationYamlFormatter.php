<?php

namespace Drupal\Tests\dhl_location_finder\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\dhl_location_finder\Service\DhlLocationYamlFormatter;

class DhlLocationYamlFormatterTest extends UnitTestCase {

  public function testFormatLocationsAsYaml() {
    $service = new DhlLocationYamlFormatter();
    $locations = [
      [
        'name' => 'Packstation 103',
        'place' => ['address' => ['countryCode' => 'DE','postalCode' => '01067','addressLocality' => 'Dresden','streetAddress' => 'Falkenstr. 10']],
        'openingHours' => [
            ['opens' => '00:00:00', 'closes' => '23:59:00', 'dayOfWeek' => 'http://schema.org/Sunday'],
            ['opens' => '00:00:00', 'closes' => '23:59:00', 'dayOfWeek' => 'http://schema.org/Monday'],
            ['opens' => '00:00:00', 'closes' => '23:59:00', 'dayOfWeek' => 'http://schema.org/Tuesday'],
            ['opens' => '00:00:00', 'closes' => '23:59:00', 'dayOfWeek' => 'http://schema.org/Wednesday'],
          ],
      ],
    ];

    $yaml_output = $service->formatLocationsAsYaml($locations);
    $this->assertStringContainsString('Packstation 103', $yaml_output);
  }
}
