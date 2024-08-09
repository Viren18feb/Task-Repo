# DHL Location Finder Drupal Module

## Overview

The **DHL Location Finder** module provides a Drupal interface to interact with the DHL Location Finder API. Users can enter a country, city, and postal code to retrieve a list of DHL locations. The module filters out locations that do not operate on weekends and those with odd-numbered street addresses. The results are displayed in YAML format.

## Features

- **API Integration:** Interacts with the DHL Location Finder API to retrieve location data.
- **Filtering:** Automatically filters out locations that do not operate on weekends or have an odd-numbered address.
- **YAML Output:** Displays the filtered list of locations in YAML format.
- **PSR-12 Compliance:** The code is fully compliant with PSR-12 standards.
- **Test Coverage:** The module includes unit and functional tests for all core functionalities.

## Requirements

- **Drupal:** 10.x
- **PHP:** 8.x

## Installation

1. **Download and Extract:**
   - Download the module as a `.zip` file.
   - Extract the contents to your Drupal site's `modules/custom` directory.

2. **Enable the Module:**
   - Navigate to `Extend` in the Drupal admin interface.
   - Search for **DHL Location Finder** and enable it.

3. **Dependencies:**
   - The module requires the `http_client` service for making API requests. Ensure it is available in your environment.

## Usage

1. **Navigate to the Form:**
   - After enabling the module, navigate to `/dhl-location-finder` to access the form.

2. **Submit the Form:**
   - Enter the country, city, and postal code.
   - Submit the form to get the list of DHL locations that meet the criteria.

3. **View Results:**
   - The results will be displayed in YAML format on the same page.

## Testing

The module includes unit and functional tests:

- **Unit Tests:** These are located in the `tests/src/Unit` directory and can be run using PHPUnit.
- **Functional Tests:** These are located in the `tests/src/Functional` directory and can be run using Drupal's test runner.

To run the tests:
```bash
$ ./vendor/bin/phpunit -c core modules/custom/dhl_location_finder/tests
