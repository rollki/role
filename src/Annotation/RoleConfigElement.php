<?php

namespace Drupal\role\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a RoleConfigElement annotation object.
 *
 * @Annotation
 *
 * @see \Drupal\role\Service\RoleConfigElementManager
 * @see \Drupal\role\RoleConfigElementInterface
 * @see \Drupal\role\RoleConfigElementBase
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
   * The human-readable name of the RoleConfigElement type.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
