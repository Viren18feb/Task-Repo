<?php

namespace Drupal\dhl_location_finder\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dhl_location_finder\Service\DhlLocationApiClient;
use Drupal\dhl_location_finder\Service\DhlLocationFilterService;
use Drupal\dhl_location_finder\Service\CountryService;
use Drupal\dhl_location_finder\Service\DhlLocationYamlFormatter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DhlLocationFinderForm extends FormBase {

  protected $dhlLocationApiClient;
  protected $dhlLocationFilterService;
  protected $dhlLocationYamlFormatter;
  protected $countryService;

  public function __construct(CountryService $countryService,DhlLocationApiClient $dhlLocationApiClient, DhlLocationFilterService $dhlLocationFilterService, DhlLocationYamlFormatter $dhlLocationYamlFormatter) {
    $this->countryService = $countryService;
    $this->dhlLocationApiClient = $dhlLocationApiClient;
    $this->dhlLocationFilterService = $dhlLocationFilterService;
    $this->dhlLocationYamlFormatter = $dhlLocationYamlFormatter;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dhl_location_finder.country_service'),
      $container->get('dhl_location_finder.api_service'),
      $container->get('dhl_location_finder.filter_service'),
      $container->get('dhl_location_finder.yaml_formatter')
    );
  }

  public function getFormId() {
    return 'dhl_location_finder_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $countries = $this->countryService->getStandardCountries();

    $form['country'] = [
      '#type' => 'select',
      '#title' => $this->t('Select a country'),
      '#options' => $countries,
      '#required' => TRUE,
    ];

    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#required' => TRUE,
    ];

    $form['postal_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Postal Code'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Find Locations'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $country = $form_state->getValue('country');
    $city = $form_state->getValue('city');
    $postal_code = $form_state->getValue('postal_code');

    $locations = $this->dhlLocationApiClient->fetchLocations($country, $city, $postal_code);
    if(isset($locations) && !empty($locations)){
      $filtered_locations = $this->dhlLocationFilterService->filterLocations($locations);
      $yaml_output = $this->dhlLocationYamlFormatter->formatLocationsAsYaml($filtered_locations);
      // Create a YAML file and save it to the public file system.
      $file_name = 'dhl_locations_' . $postal_code . '.yaml';
      $public_scheme = 'public://'.$file_name;

      // Get the real path.
      $real_path = \Drupal::service('file_system')->realpath($public_scheme);
      file_put_contents($real_path, $yaml_output);

      // Provide the download link.
      // Trigger download of the YAML file.
      $response = new BinaryFileResponse($real_path);
      $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file_name);
      $response->send();
    }else{
      $this->messenger()->addStatus($this->t('DHL Locations not found'));
    }
    $form_state->setRebuild();
    return $form;
  }
}
