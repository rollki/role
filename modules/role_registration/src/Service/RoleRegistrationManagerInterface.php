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

  /**
   * Get register display base path.
   *
   * @return string
   *   Base path.
   */
  public function getRegisterDisplayBasePath();

  /**
   * Wrapper to check if alias exist.
   *
   * @param string $alias
   *   Alias to check.
   *
   * @return bool
   *   True if alias exist, otherwise FALSE.
   */
  public function isAliasExist($alias);

  /**
   * Update alias.
   *
   * @param string $source
   *   Source path.
   * @param string $alias
   *   Alias path.
   */
  public function updateAlias($source, $alias);

  /**
   * Delete alias by source.
   *
   * @param string $source
   *   Source path.
   */
  public function deleteAliasBySource($source);

}
