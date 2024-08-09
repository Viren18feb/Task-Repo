<?php

namespace Drupal\Tests\dhl_location_finder\Functional;

use Drupal\Tests\BrowserTestBase;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Client;

/**
 * Tests the functionality of the DHL Location Finder module.
 *
 * @group dhl_location_finder
 */
class DhlLocationFinderFunctionalTest extends BrowserTestBase {

  /**
   * The modules to enable.
   *
   * @var array
   */
  protected static $modules = ['dhl_location_finder'];

  /**
   * A mock HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $mockHttpClient;

  /**
   * A history container for the requests.
   *
   * @var array
   */
  protected $requestHistory = [];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Setup a mock HTTP client with a history container.
    $handlerStack = HandlerStack::create();
    $history = Middleware::history($this->requestHistory);
    $handlerStack->push($history);

    $this->mockHttpClient = new Client(['handler' => $handlerStack]);

    // Override the http_client service with our mock client.
    \Drupal::getContainer()->set('http_client', $this->mockHttpClient);
  }

  /**
   * Test the DHL Location Finder form submission and output.
   */
  public function testFormSubmissionAndYamlOutput() {
    // Mock API response.
    $mockResponse = new Response(200, [], json_encode([
      'locations' => [
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
        [
          'name' => 'Packstation 104',
          'place' => ['address' => ['countryCode' => 'DE','postalCode' => '01067','addressLocality' => 'Dresden','streetAddress' => 'Maximilianstr. 7']],
          'openingHours' => [
            ['opens' => '00:00:00', 'closes' => '23:59:00', 'dayOfWeek' => 'http://schema.org/Sunday'],
            ['opens' => '00:00:00', 'closes' => '23:59:00', 'dayOfWeek' => 'http://schema.org/Monday'],
            ['opens' => '00:00:00', 'closes' => '23:59:00', 'dayOfWeek' => 'http://schema.org/Tuesday'],
            ['opens' => '00:00:00', 'closes' => '23:59:00', 'dayOfWeek' => 'http://schema.org/Wednesday'],
          ],
        ],
      ],
    ]));

    $this->mockHttpClient->getHandlerStack()->setHandler(
      new MockHandler([$mockResponse])
    );

    // Visit the form page.
    $this->drupalGet('/dhl-location-finder');

    // Verify that the form is present.
    $this->assertSession()->fieldExists('country');
    $this->assertSession()->fieldExists('city');
    $this->assertSession()->fieldExists('postal_code');

    // Fill out the form and submit it.
    $edit = [
      'country' => 'DE',
      'city' => 'Dresden',
      'postal_code' => '01067',
    ];
    $this->submitForm($edit, t('Find Locations'));

    // Check if the mock API was called.
    $this->assertCount(1, $this->requestHistory);
    $this->assertEquals('GET', $this->requestHistory[0]['request']->getMethod());

    // Verify the output.
    $this->assertSession()->pageTextContains('locationName: Packstation 103');
    $this->assertSession()->pageTextNotContains('locationName: Packstation 104');
  }
}
