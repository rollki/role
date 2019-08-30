<?php

namespace Drupal\role\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\role\Service\RoleControlManager;
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
   * TotalRoleForm constructor.
   */
  public function __construct(EntityDisplayRepositoryInterface $entity_display_repository, RoleControlManager $role_manager) {
    $this->entityDisplayRepository = $entity_display_repository;
    $this->roleControlManager = $role_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_display.repository'),
      $container->get('role.control_manager')
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
    $form_mode_field_name = $this->roleControlManager->getExtraFieldKey('account_form_mode');
    $form['account'][$form_mode_field_name] = [
      '#type' => 'select',
      '#title' => $this->t('Form mode'),
      '#options' => $this->getUserFormModesOptions(),
      '#default_value' => $role->getThirdPartySetting('role', $form_mode_field_name),
      '#description' => $this->t('Select which form mode to use on the user account edit form'),
    ];

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
   * Role entity builder.
   */
  public function controlRoleBuilder($entity_type, RoleInterface $role, &$form, FormStateInterface &$form_state) {
    foreach ($this->roleControlManager->getExtraFields() as $field_name) {
      if ($form_state->hasValue($field_name)) {
        $role->setThirdPartySetting('role', $field_name, $form_state->getValue($field_name));
      }
    }
  }

}
