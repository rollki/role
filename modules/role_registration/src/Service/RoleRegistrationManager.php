<?php

namespace Drupal\role_registration\Service;

use Drupal\user\Entity\Role;
use Drupal\user\RoleInterface;

/**
 * Class RoleRegistrationManager.
 */
class RoleRegistrationManager implements RoleRegistrationManagerInterface {

  /**
   * {@inheritdoc}
   */
  public function getRegistrationThirdPartySettings(RoleInterface $role) {
    return $role->getThirdPartySettings(self::MODULE_NAME);
  }

  /**
   * {@inheritdoc}
   */
  public function getUserRegistrationFormMode($role_id) {
    $role = Role::load($role_id);
    if (!$role) {
      return NULL;
    }
    $settings = $this->getRegistrationThirdPartySettings($role);
    if (!$settings) {
      return NULL;
    }
    return $settings['account_registration_form_mode'];
  }
}
