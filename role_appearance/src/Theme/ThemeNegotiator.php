<?php

namespace Drupal\role_appearance\Theme;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\role\Service\RoleControlManagerInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

/**
 * Role Theme Negotiator.
 */
class ThemeNegotiator implements ThemeNegotiatorInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $user;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Drupal\role\Service\RoleControlManagerInterface definition.
   *
   * @var \Drupal\role\Service\RoleControlManagerInterface
   */
  protected $roleControlManager;

  /**
   * Creates a new AdminNegotiator instance.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\role\Service\RoleControlManagerInterface $role_manager
   *   The role manager.
   */
  public function __construct(AccountInterface $user, ConfigFactoryInterface $config_factory, RoleControlManagerInterface $role_manager) {
    $this->user = $user;
    $this->configFactory = $config_factory;
    $this->roleControlManager = $role_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    $custom_theme = $this->roleControlManager->getRoleThirdPartySetting($this->user, 'role_theme');
    if (!$custom_theme) {
      $config = $this->configFactory->get('system.theme');
      $custom_theme = $config->get('default');
    }

    return $custom_theme;

  }

}
