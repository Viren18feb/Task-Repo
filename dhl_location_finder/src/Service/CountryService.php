<?php

namespace Drupal\dhl_location_finder\Service;

use Drupal\Core\Locale\CountryManager;

class CountryService {

  /**
   * The country manager service.
   *
   * @var \Drupal\Core\Locale\CountryManager
   */
  protected $countryManager;

  /**
   * Constructs a new CountryService object.
   *
   * @param \Drupal\Core\Locale\CountryManager $country_manager
   *   The country manager service.
   */
  public function __construct(CountryManager $country_manager) {
    $this->countryManager = $country_manager;
  }

  /**
   * Get the list of standard countries.
   *
   * @return array
   *   An associative array of country codes and country names.
   */
  public function getStandardCountries() {
    return $this->countryManager->getStandardList();
  }

  /**
   * Get the name of a country by its code.
   *
   * @param string $code
   *   The country code.
   *
   * @return string|null
   *   The country name, or NULL if not found.
   */
  public function getCountryByCode(string $code) {
    $countries = $this->getStandardCountries();
    return $countries[$code] ?? NULL;
  }
}
