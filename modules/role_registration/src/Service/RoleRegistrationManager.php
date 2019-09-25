<?php

namespace Drupal\role_registration\Service;

use Drupal\Core\Form\FormStateInterface;

/**
 * Class RoleRegistrationManager.
 */
class RoleRegistrationManager implements RoleRegistrationManagerInterface {

  /**
   * {@inheritdoc}
   */
  public function getRegisterDisplayBasePath() {
    return self::ROLE_REGISTRATION_BASE_REGISTER_PATH;
  }

  /**
   * {@inheritdoc}
   */
  public static function addRoleToUser(array &$form, FormStateInterface $form_state) {
    $role_id = $form_state->getValue('role_id');
    $form_state->setValue(['roles', $role_id], $role_id);
  }

}
