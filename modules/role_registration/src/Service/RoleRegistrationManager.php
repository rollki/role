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
  public static function addRoleToUser(array &$form, FormStateInterface $form_state) {
    $role_id = $form_state->getValue('role_id');
    $form_state->setValue(['roles', $role_id], $role_id);
  }

}
