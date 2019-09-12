<?php

namespace Drupal\role\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;

/**
 * Class RoleControlManager.
 */
class RoleControlManager implements RoleControlManagerInterface {

  /**
   * Extra fields.
   *
   * @var array
   */
  protected $extraFields = [];

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * RoleControlManager constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity tupe manager.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ModuleHandlerInterface $module_handler) {
    $this->entityTypeManager = $entity_type_manager;
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public function getExtraFields() {
    $extra = $this->moduleHandler->invokeAll('role_extra_field_info');
    $this->moduleHandler->alter('role_extra_field_info', $extra);
    $info = isset($extra) ? $extra : [];
    $info +=  [
      'account_form_mode' => 'account_form_mode',
      'account_view_mode' => 'account_view_mode',
    ];
    $this->moduleHandler->alter('role_extra_field_info', $extra);

    // Store in the 'static'.
    $this->extraFields = $info;

    return $this->extraFields;
  }

  /**
   * {@inheritdoc}
   */
  public function getExtraFieldKey(string $name) {
    $fields = $this->getExtraFields();

    return $fields[$name];
  }

  /**
   * {@inheritdoc}
   */
  public function getUserAccountFormMode(UserInterface $user) {
    $form_mode_field_name = $this->getExtraFieldKey('account_form_mode');
    $form_mode = $this->getRoleThirdPartySetting($user, $form_mode_field_name);
    if (!$form_mode || $form_mode === 'default') {
      return NULL;
    }

    return $form_mode;
  }

  /**
   * {@inheritdoc}
   */
  public function getUserAccountViewMode(UserInterface $user) {
    $view_mode_field_name = $this->getExtraFieldKey('account_view_mode');
    $view_mode = $this->getRoleThirdPartySetting($user, $view_mode_field_name);
    if (!$view_mode || $view_mode === 'default') {
      return NULL;
    }

    return $view_mode;
  }

  /**
   * {@inheritdoc}
   */
  public function getUserPriorityRole(AccountInterface $user) {
    // TODO Implemented only for no more than 2 roles.
    $role_storage = $this->entityTypeManager->getStorage('user_role');
    $roles = $user->getRoles();

    $role = NULL;
    if (count($roles) === 1 && in_array(AccountInterface::AUTHENTICATED_ROLE, $roles)) {
      $role = $role_storage->load(AccountInterface::AUTHENTICATED_ROLE);
    }
    else {
      array_splice($roles, array_search(AccountInterface::AUTHENTICATED_ROLE, $roles), 1);
      $first_role = reset($roles);
      $role = $role_storage->load($first_role);
    }
    return $role;
  }

  /**
   * {@inheritdoc}
   */
  public function getRoleThirdPartySetting(AccountInterface $user, string $config) {
    /** @var \Drupal\user\RoleInterface $role */
    $role = $this->getUserPriorityRole($user);
    $settings = $role->getThirdPartySettings(self::MODULE_NAME);

    return $settings[$config] ?? NULL;
  }
}
