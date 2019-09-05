<?php

namespace Drupal\role\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
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
   * RoleControlManager constructor.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getExtraFields() {
    return [
      'account_form_mode' => 'account_form_mode',
      'account_view_mode' => 'account_view_mode',
    ];
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
    $third_party_settings = $this->getRoleThirdPartySettings($user);
    if (!$third_party_settings) {
      return NULL;
    }
    $form_mode_field_name = $this->getExtraFieldKey('account_form_mode');
    $form_mode = $third_party_settings[$form_mode_field_name];
    if ($form_mode === 'default') {
      return NULL;
    }

    return $form_mode;
  }

  /**
   * {@inheritdoc}
   */
  public function getUserAccountViewMode(UserInterface $user) {
    $third_party_settings = $this->getRoleThirdPartySettings($user);
    if (!$third_party_settings) {
      return NULL;
    }
    $view_mode_field_name = $this->getExtraFieldKey('account_view_mode');
    $view_mode = $third_party_settings[$view_mode_field_name];
    if ($view_mode === 'default') {
      return NULL;
    }

    return $view_mode;
  }

  /**
   * {@inheritdoc}
   */
  public function getUserPriorityRole(UserInterface $user) {
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
  public function getRoleThirdPartySettings(UserInterface $user) {
    /** @var \Drupal\user\RoleInterface $role */
    $role = $this->getUserPriorityRole($user);

    return $role->getThirdPartySettings(self::MODULE_NAME);
  }

}
