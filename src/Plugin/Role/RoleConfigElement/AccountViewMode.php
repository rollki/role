<?php

namespace Drupal\role\Plugin\Role\RoleConfigElement;

use Drupal\Core\Annotation\Translation;
use Drupal\role\Annotation\RoleConfigElement;
use Drupal\role\Plugin\RoleConfigElementBase;
use Drupal\role\Service\RoleControlManagerInterface;
use Drupal\user\RoleInterface;

/**
 * Provides a role config elements.
 *
 * @RoleConfigElement(
 *   id = "account_view_mode",
 *   title = @Translation("Account view mode"),
 * )
 */
class AccountViewMode extends RoleConfigElementBase {

  /**
   * {@inheritdoc}
   */
  public function attachElement(&$form, RoleInterface $role) {
    $plugin_id = $this->getPluginId();
    $form['account'][$plugin_id] = [
      '#type' => 'select',
      '#title' => $this->t('View mode'),
      '#options' => $this->getUserViewModesOptions(),
      '#description' => $this->t('Select which view mode to use on the user page'),
      '#default_value' => $role->getThirdPartySetting(RoleControlManagerInterface::MODULE_NAME, $plugin_id),
    ];
  }

  /**
   * Get user view modes options.
   *
   * @return array
   *   View mode options.
   */
  public function getUserViewModesOptions() {
    // Load user view modes.
    $user_view_modes = $this->entityDisplayRepository->getViewModeOptionsByBundle('user', 'user');
    $user_view_modes_options = ['default' => $this->t('Default')];
    foreach ($user_view_modes as $key => $view_mode_label) {
      $user_view_modes_options[$key] = $view_mode_label;
    }

    return $user_view_modes_options;
  }

}
