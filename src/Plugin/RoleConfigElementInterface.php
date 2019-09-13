<?php

namespace Drupal\role\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\user\RoleInterface;

/**
 * Defines the interface for text processing filter plugins.
 *
 * @see plugin_api
 */
interface RoleConfigElementInterface extends PluginInspectionInterface {

  /**
   * Attach element to the form.
   */
  public function attachElement(&$form, RoleInterface $role);

}
