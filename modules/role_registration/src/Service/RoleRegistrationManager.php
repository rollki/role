<?php

namespace Drupal\role_registration\Service;

use Drupal\Core\Form\FormStateInterface;
use Drupal\user\RoleInterface;

/**
 * Class RoleRegistrationManager.
 */
class RoleRegistrationManager implements RoleRegistrationManagerInterface {

  /**
   * {@inheritdoc}
   */
  public static function addRoleToUser(array &$form, FormStateInterface $form_state) {
    $role_id = $form_state->getValue('role_id');
    $form_state->setValue(['roles', $role_id], $role_id);
  }

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
