<?php

namespace Drupal\role_registration\Plugin\Role\RoleConfigElement;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Render\Element\PathElement;
use Drupal\Core\Routing\RequestContext;
use Drupal\role\Plugin\RoleConfigElementBase;
use Drupal\role\Service\RoleControlManagerInterface;
use Drupal\role_registration\Service\RoleRegistrationManagerInterface;
use Drupal\user\RoleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a role config element.
 *
 * @RoleConfigElement(
 *   id = "account_registration_alias",
 *   title = @Translation("Registration alias"),
 * )
 */
class RegistrationAlias extends RoleConfigElementBase {

  /**
   * The RoleRegistrationManager service.
   *
   * @var \Drupal\role_registration\Service\RoleRegistrationManager
   */
  protected $roleRegistrationManager;

  /**
   * The request context.
   *
   * @var \Drupal\Core\Routing\RequestContext
   */
  protected $requestContext;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityDisplayRepositoryInterface $entity_display_repository,
    RoleControlManagerInterface $role_manager,
    RoleRegistrationManagerInterface $registration_manager,
    RequestContext $request_context
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_display_repository, $role_manager);

    $this->roleRegistrationManager = $registration_manager;
    $this->requestContext = $request_context;
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
      $container->get('role_registration.manager'),
      $container->get('router.request_context')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function attachElement(&$form, RoleInterface $role) {
    $plugin_id = $this->getPluginId();
    $register_page_url = $this->roleRegistrationManager->getRegisterDisplayBasePath() . '/' . $role->id();

    $form['account']['registration'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Registration'),
    ];

    $form['account']['registration'][$plugin_id] = [
      '#type' => 'textfield',
      '#title' => $this->t('Registration page alias'),
      '#convert_path' => PathElement::CONVERT_NONE,
      '#default_value' => $role->getThirdPartySetting($this->pluginDefinition['provider'], $plugin_id),
      '#description' => $this->t('Register page url for this role is @url', ['@url' => $register_page_url]),
      '#field_prefix' => $this->requestContext->getCompleteBaseUrl(),
      '#required' => FALSE,
      '#states' => [
        'visible' => [
          [':input[name="account_registration_status"]' => ['checked' => TRUE]],
        ],
      ],
      '#weight' => 3,
    ];
  }

}
