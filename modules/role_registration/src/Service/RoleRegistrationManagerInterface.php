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

}
