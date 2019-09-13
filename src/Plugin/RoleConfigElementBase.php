<?php

namespace Drupal\role\Plugin;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\role\Service\RoleControlManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a base class for Role plugins.
 *
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
   * Drupal\role\Service\RoleControlManagerInterface definition.
   *
   * @var \Drupal\role\Service\RoleControlManagerInterface
   */
  protected $roleControlManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityDisplayRepositoryInterface $entity_display_repository, RoleControlManagerInterface $role_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityDisplayRepository = $entity_display_repository;
    $this->roleControlManager = $role_manager;
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
      $container->get('role.control_manager')
    );
  }

}
