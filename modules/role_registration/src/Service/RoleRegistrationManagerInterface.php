<?php

namespace Drupal\role_registration\Service;

use Drupal\Core\Form\FormStateInterface;

/**
 * Base interface definition for RoleRegistrationManagerInterface service.
 */
interface RoleRegistrationManagerInterface {

  /**
   * The module name.
   */
  const MODULE_NAME = 'role_registration';

  /**
   * The base register path.
   */
  const ROLE_REGISTRATION_BASE_REGISTER_PATH = '/user/register';

  /**
   * Inject user role in the creation process.
   *
   * @param array $form
   *   Register form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public static function addRoleToUser(array &$form, FormStateInterface $form_state);

}
