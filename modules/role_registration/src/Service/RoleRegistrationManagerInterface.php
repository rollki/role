<?php

namespace Drupal\role_registration\Service;

use Drupal\user\UserInterface;

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
   * Gets all third-party settings which was set for register page on the role edit page.
   *
   * @return array
   *   An array of key-value pairs.
   */
  public function getRegistrationThirdPartySettings(UserInterface $user);

  /**
   * Get user registration form mode based on his roles.
   *
   * @param $role_id
   *
   * @return string
   *   User form mode.
   */
  public function getUserRegistrationFormMode($role_id);
}
