<?php
/**
 * @file
 * Hooks provided by the Role module.
 */

/**
 * Add fileds info to role config entity.
 *
 * @see hook_role_extra_field_alter()
 *
 * @return array
 *   The array structure is identical to that of the return value of
 *   Drupal\role\Service\RoleControlManagerInterface::getExtraFields().
 */
function hook_role_extra_field() {
  return [];
}

/**
 * Alter fileds info of role config entity..
 *
 * @param array $info
 *   The array structure is identical to that of the return value of
 *   \Drupal\role\Service\RoleControlManagerInterface::getExtraFields().
 *
 * @see hook_role_extra_field()
 */
function hook_role_extra_field_alter(&$info) {}
