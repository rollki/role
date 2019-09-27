<?php

namespace Drupal\role_appearance\Plugin\Role\RoleConfigElement;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\role\Plugin\RoleConfigElementBase;
use Drupal\role\Service\RoleControlManager;
use Drupal\user\RoleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a role config element.
 *
 * @RoleConfigElement(
 *   id = "role_theme",
 *   title = @Translation("Role theme"),
 * )
 */
class RoleTheme extends RoleConfigElementBase {

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityDisplayRepositoryInterface $entity_display_repository, RoleControlManager $role_manager, ThemeHandlerInterface $theme_handler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_display_repository, $role_manager);
    $this->themeHandler = $theme_handler;
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
      $container->get('theme_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function attachElement(&$form, RoleInterface $role) {
    $plugin_id = $this->getPluginId();
    $themes = $this->themeHandler->listInfo();
    foreach ($themes as $key => $value) {
      $options[$key] = $value->info['name'];
    }
    $form['appearance'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Appearance settings'),
    ];
    $form['appearance'][$plugin_id] = [
      '#type' => 'select',
      '#title' => $this->t('Installed themes'),
      '#description' => $this->t('Select which themes to use for user'),
      '#default_value' => $role->getThirdPartySetting($this->pluginDefinition['provider'], $plugin_id),
      '#options' => $options,
    ];
  }

}
