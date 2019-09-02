<?php

namespace Drupal\role\Service;

use Drupal\user\UserInterface;

/**
 * Base interface definition for RoleControlManager service.
 */
interface RoleControlManagerInterface {

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
   * Get user priority role.
   */
  public function getUserPriorityRole(UserInterface $user);

}
