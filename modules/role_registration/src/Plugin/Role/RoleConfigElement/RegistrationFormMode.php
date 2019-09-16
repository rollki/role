<?php

namespace Drupal\role_registration\Plugin\Role\RoleConfigElement;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\role\Plugin\RoleConfigElementBase;
use Drupal\role\Service\RoleControlManagerInterface;
use Drupal\role_registration\Service\RoleRegistrationManagerInterface;
use Drupal\user\RoleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a role config element.
 *
 * @RoleConfigElement(
 *   id = "account_registration_form_mode",
 *   title = @Translation("Registration form mode"),
 * )
 */
class RegistrationFormMode extends RoleConfigElementBase {

  /**
   * The RoleRegistrationManager service.
   *
   * @var \Drupal\role_registration\Service\RoleRegistrationManager
   */
  protected $roleRegistrationManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityDisplayRepositoryInterface $entity_display_repository,
    RoleControlManagerInterface $role_manager,
    RoleRegistrationManagerInterface $registration_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_display_repository, $role_manager);

    $this->roleRegistrationManager = $registration_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_display.repository'),
      $container->get('role.control_manager'),
      $container->get('role_registration.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function attachElement(&$form, RoleInterface $role) {
    $registration_third_party_settings = $this->roleRegistrationManager->getRegistrationThirdPartySettings($role);
    $plugin_id = $this->getPluginId();

    $form['account']['registration'][$plugin_id] = [
      '#type' => 'select',
      '#title' => $this->t('Registration Form mode'),
      '#options' => $this->roleControlManager->getUserFormModesOptions(),
      '#description' => $this->t('Select which form mode to use on the user registration page'),
      '#states' => [
        'visible' => [
          [':input[name="registration_status"]' => ['checked' => TRUE]],
        ],
      ],
      '#default_value' => $registration_third_party_settings[$plugin_id],
    ];
  }

}
