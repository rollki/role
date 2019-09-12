<?php

namespace Drupal\role\Service;

use Drupal\Component\Plugin\FallbackPluginManagerInterface;
use Drupal\Component\Plugin\PluginManagerInterface;

/**
 * Base interface definition for RoleConfigElementManage service.
 */
interface RoleConfigElementManagerinterface extends PluginManagerInterface, FallbackPluginManagerInterface {

  /**
   * Attach all Role Config Elements.
   */
  public function getRoleConfigElements();

}
