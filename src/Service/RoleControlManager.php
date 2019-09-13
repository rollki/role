<?php

namespace Drupal\role\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\role\Plugin\RoleConfigElementManager;
use Drupal\user\UserInterface;

/**
 * Class RoleControlManager.
 */
class RoleControlManager implements RoleControlManagerInterface {

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
   * Drupal\role\Plugin\RoleConfigElementManager definition.
   *
   * @var \Drupal\role\Plugin\RoleConfigElementManager
   */
  protected $roleConfigElementManager;

  /**
   * RoleControlManager constructor.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ModuleHandlerInterface $module_handler, RoleConfigElementManager $role_config_element_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->moduleHandler = $module_handler;
    $this->roleConfigElementManager = $role_config_element_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getExtraFields() {
    $extra_fields = [];
    $definitions = $this->roleConfigElementManager->getDefinitions();
    foreach ($definitions as $definition) {
      $extra_fields[$definition['id']] = $definition['id'];
    }

    return $extra_fields;
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
    if ($user->isAnonymous()) {
      return $role_storage->load(AccountInterface::ANONYMOUS_ROLE);
    }
    if (count($roles) === 1 && in_array(AccountInterface::AUTHENTICATED_ROLE, $roles)) {
      return $role_storage->load(AccountInterface::AUTHENTICATED_ROLE);
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
    if (!$role) {
      return NULL;
    }
    $settings = $role->getThirdPartySettings(self::MODULE_NAME);

    return $settings[$config] ?? NULL;
  }

}
