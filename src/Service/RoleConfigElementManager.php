<?php

namespace Drupal\role\Service;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\user\RoleInterface;

/**
 * Manager class for the platform plugins.
 */
class RoleConfigElementManager extends DefaultPluginManager
{

  /**
   * Constructs an role  object.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler)
  {
    parent::__construct('Plugin/Role/RoleConfigElement', $namespaces, $module_handler, 'Drupal\role\RoleConfigElementInterface', 'Drupal\role\Annotation\RoleConfigElement');
    $this->setCacheBackend($cache_backend, 'role_config_element_plugins');
  }

  /**
   * Attach all Role Config Elements.
   */
  public function getRoleConfigElements(&$form, RoleInterface $role) {
    $definitions = $this->getDefinitions();
    foreach ($definitions as $definition) {
      $instance = $this->createInstance($definition['id']);
      $instance->attachElement($form, $role);
    }
  }
}
