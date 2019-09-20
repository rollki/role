<?php

namespace Drupal\role\Service;

use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;

/**
 * Base interface definition for RoleControlManager service.
 */
interface RoleControlManagerInterface {

  /**
   * The module name.
   */
  const MODULE_NAME = 'role';

  /**
   * Get extra fields.
   *
   * @return array
   *   Extra fields names.
   */
  public function getExtraFields();

  /**
   * Get extra field key.
   *
   * @param string $name
   *   Field name.
   *
   * @return string
   *   Extra field key.
   */
  public function getExtraFieldKey(string $name);

  /**
   * Get user form mode based on it roles.
   */
  public function getUserAccountFormMode(UserInterface $user);

  /**
   * Get user view mode based on it roles.
   *
   * @return string
   *   User view mode.
   */
  public function getUserAccountViewMode(UserInterface $user);

  /**
   * Get current user priority role.
   *
   * @return object
   *   User role.
   */
  public function getUserPriorityRole(AccountInterface $user);

  /**
   * Gets all third-party settings of a given module based on it roles.
   *
   * @return array
   *   An array of key-value pairs.
   */
  public function getRoleThirdPartySetting(AccountInterface $user, string $config);

  /**
   * Get user form modes options.
   *
   * @return array
   *   Form mode options.
   */
  public function getUserFormModesOptions();

}
