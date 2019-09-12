<?php

namespace Drupal\role;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\user\RoleInterface;

/**
 * Defines the interface for text processing filter plugins.
 *
 * @see \Drupal\role\RoleConfigElementBase
 * @see plugin_api
 */
interface RoleConfigElementInterface extends PluginInspectionInterface {

  /**
   * @param $form
   * @param RoleInterface $role
   * @return mixed
   */
  public function attachElement(&$form, RoleInterface $role);
}
