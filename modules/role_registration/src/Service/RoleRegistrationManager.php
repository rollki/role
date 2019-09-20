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
  public function getRegistrationThirdPartySettings(RoleInterface $role, $setting_name = NULL) {
    $third_party_settings = $role->getThirdPartySettings(self::MODULE_NAME);
    if ($setting_name) {
      return $third_party_settings[$setting_name];
    }
    return $third_party_settings;
  }

}
