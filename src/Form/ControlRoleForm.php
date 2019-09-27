<?php

namespace Drupal\role\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\role\Service\RoleControlManager;
use Drupal\role\Service\RoleControlManagerInterface;
use Drupal\role\Plugin\RoleConfigElementManager;
use Drupal\user\RoleForm;
use Drupal\user\RoleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Role form for the user role.
 */
class ControlRoleForm extends RoleForm implements ContainerInjectionInterface {

  /**
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * Drupal\role\Service\RoleControlManager definition.
   *
   * @var \Drupal\role\Service\RoleControlManager
   */
  protected $roleControlManager;

  /**
   * Drupal\role\Plugin\RoleConfigElementManager definition.
   *
   * @var \Drupal\role\Plugin\RoleConfigElementManager
   */
  protected $roleConfigElementManager;

  /**
   * TotalRoleForm constructor.
   */
  public function __construct(
    EntityDisplayRepositoryInterface $entity_display_repository,
    RoleControlManager $role_manager,
    RoleConfigElementManager $role_config_element_manager
  ) {
    $this->entityDisplayRepository = $entity_display_repository;
    $this->roleControlManager = $role_manager;
    $this->roleConfigElementManager = $role_config_element_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_display.repository'),
      $container->get('role.control_manager'),
      $container->get('plugin.manager.role_config_element')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var \Drupal\user\RoleInterface $role */
    $role = $this->entity;

    $form['account'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Account settings'),
    ];

    $this->roleConfigElementManager->getRoleConfigElements($form, $role);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\user\RoleInterface $role */
    $role = $form_state->getFormObject()->getEntity();
    $module_name = RoleControlManagerInterface::MODULE_NAME;
    foreach ($this->roleControlManager->getExtraFields() as $field_name) {
      if ($form_state->hasValue($field_name)) {
        $plugin_def = $this->roleConfigElementManager->getDefinition($field_name);
        if (isset($plugin_def) && !empty($plugin_def['provider'])) {
          $module_name = $plugin_def['provider'];
        }
        $role->setThirdPartySetting($module_name, $field_name, $form_state->getValue($field_name));
      }
    }
    parent::submitForm($form, $form_state);
  }

}
