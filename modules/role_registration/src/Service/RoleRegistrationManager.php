<?php

namespace Drupal\role_registration\Service;

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
    // TODO: Implement getUserRegistrationFormMode() method.
  }
}
