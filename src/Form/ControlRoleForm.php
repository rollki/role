<?php

namespace Drupal\role\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\role\Service\RoleControlManager;
use Drupal\role\Service\RoleControlManagerInterface;
use Drupal\role\Service\RoleConfigElementManager;
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
   * Drupal\role\Service\RoleConfigElementManager definition.
   *
   * @var \Drupal\role\Service\RoleConfigElementManager
   */
  protected $roleConfigElementManager;

  /**
   * TotalRoleForm constructor.
   */
  public function __construct(EntityDisplayRepositoryInterface $entity_display_repository, RoleControlManager $role_manager, RoleConfigElementManager $role_config_element_manager) {
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
    $form_mode_field_name = $this->roleControlManager->getExtraFieldKey('account_form_mode');
    $view_mode_field_name = $this->roleControlManager->getExtraFieldKey('account_view_mode');

    $form['account'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Account settings'),
    ];
/*    $form['account'][$form_mode_field_name] = [
      '#type' => 'select',
      '#title' => $this->t('Form mode'),
      '#options' => $this->getUserFormModesOptions(),
      '#default_value' => $role->getThirdPartySetting(RoleControlManagerInterface::MODULE_NAME, $form_mode_field_name),
      '#description' => $this->t('Select which form mode to use on the user account edit form'),
    ];
    $form['account'][$view_mode_field_name] = [
      '#type' => 'select',
      '#title' => $this->t('View mode'),
      '#options' => $this->getUserViewModesOptions(),
      '#default_value' => $role->getThirdPartySetting(RoleControlManagerInterface::MODULE_NAME, $view_mode_field_name),
      '#description' => $this->t('Select which view mode to use on the user page'),
    ];*/

    $this->roleConfigElementManager->getRoleConfigElements($form, $role);

    $form['#entity_builders'][] = '::controlRoleBuilder';
    return $form;
  }

  /**
   * Get user form modes options.
   *
   * @return array
   *   Form mode options.
   */
  public function getUserFormModesOptions() {
    // Load user display modes.
    $user_form_modes = $this->entityDisplayRepository->getFormModeOptionsByBundle('user', 'user');
    $user_form_modes_options = ['default' => $this->t('Default')];
    foreach ($user_form_modes as $key => $form_mode_label) {
      $user_form_modes_options[$key] = $form_mode_label;
    }

    return $user_form_modes_options;
  }

  /**
   * Get user view modes options.
   *
   * @return array
   *   View mode options.
   */
  public function getUserViewModesOptions() {
    // Load user view modes.
    $user_view_modes = $this->entityDisplayRepository->getViewModeOptionsByBundle('user', 'user');
    $user_view_modes_options = ['default' => $this->t('Default')];
    foreach ($user_view_modes as $key => $view_mode_label) {
      $user_view_modes_options[$key] = $view_mode_label;
    }

    return $user_view_modes_options;
  }

  /**
   * Role entity builder.
   */
  public function controlRoleBuilder($entity_type, RoleInterface $role, &$form, FormStateInterface &$form_state) {
    foreach ($this->roleControlManager->getExtraFields() as $field_name) {
      if ($form_state->hasValue($field_name)) {
        $role->setThirdPartySetting(RoleControlManagerInterface::MODULE_NAME, $field_name, $form_state->getValue($field_name));
      }
    }
  }

}
