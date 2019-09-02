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
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
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
    /** @var Drupal\user\Entity\RoleInterface $role */
    $role = $this->getUserPriorityRole($user);
    $third_party_settings = $role->getThirdPartySettings('role');
    if (!$third_party_settings) {
      return;
    }
    $form_mode_field_name = $this->getExtraFieldKey('account_form_mode');
    return $third_party_settings[$form_mode_field_name];
  }

  /**
   * Get user priority role.
   */
  public function getUserPriorityRole(UserInterface $user) {
    // @todo Implemented only for no more than 2 roles.
    $roleStorage = $this->entityTypeManager->getStorage('user_role');
    $roles = $user->getRoles();
    if (count($roles) === 1 && in_array(AccountInterface::AUTHENTICATED_ROLE, $roles)) {
      $role = $roleStorage->load(AccountInterface::AUTHENTICATED_ROLE);
    }
    else {
      array_splice($roles, array_search(AccountInterface::AUTHENTICATED_ROLE, $roles), 1);
      $first_role = reset($roles);
      $role = $roleStorage->load($first_role);
    }
    return $role;
  }

}
