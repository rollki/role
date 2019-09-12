<?php

namespace Drupal\role_registration\Service;

use Drupal\user\RoleInterface;

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
   * @param \Drupal\user\RoleInterface $role
   *
   * @param null $setting_name
   *
   * @return array
   *   An array of key-value pairs.
   */
  public function getRegistrationThirdPartySettings(RoleInterface $role, $setting_name = NULL);
}
