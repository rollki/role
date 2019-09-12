<?php

namespace Drupal\role\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a DevelDumper annotation object.
 *
 * @Annotation
 *
 * @see \Drupal\devel\DevelDumperPluginManager
 * @see \Drupal\devel\DevelDumperInterface
 * @see \Drupal\devel\DevelDumperBase
 * @see plugin_api
 */
class RoleConfigElement extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the DevelDumper type.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $label;
}
