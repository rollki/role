<?php

namespace Drupal\role\Service;

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
   * Get user form mode based on his roles.
   *
   * @return string
   *   User form mode.
   */
  public function getUserAccountFormMode(UserInterface $user);

  /**
   * Get user view mode based on his roles.
   *
   * @return string
   *   User form mode.
   */
  public function getUserAccountViewMode(UserInterface $user);

  /**
   * Get user priority role.
   */
  public function getUserPriorityRole(UserInterface $user);

  /**
   * Gets all third-party settings of a given module based on his roles.
   */
  public function getRoleThirdPartySettings(UserInterface $user);

}
