<?php

namespace Drupal\role;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\role\Service\RoleConfigElementManager;
use Drupal\role\Service\RoleControlManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a base class for Role plugins.
 *
 * @see \Drupal\role\RoleConfigElementInterface
 * @see role_api
 */
abstract class RoleConfigElementBase extends PluginBase implements RoleConfigElementInterface, ContainerFactoryPluginInterface {

  /**
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * Drupal\role\Service\RoleControlManager definition.
   *
   * @var \Drupal\role\Service\RoleControlManager
   */
  protected $roleControlManager;

  /**
   * Drupal\role\Service\RoleConfigElementManager definition.
   *
   * @var \Drupal\role\Service\RoleConfigElementManager
   */
  protected $roleConfigElementManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityDisplayRepositoryInterface $entity_display_repository, RoleControlManager $role_manager, RoleConfigElementManager $role_config_element_manager)  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityDisplayRepository = $entity_display_repository;
    $this->roleControlManager = $role_manager;
    $this->roleConfigElementManager = $role_config_element_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_display.repository'),
      $container->get('role.control_manager'),
      $container->get('plugin.manager.role_config_element')
    );
  }
}
