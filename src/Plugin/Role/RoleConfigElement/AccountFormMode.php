<?php

namespace Drupal\role\Plugin\Role\RoleConfigElement;

use Drupal\role\Plugin\RoleConfigElementBase;
use Drupal\role\Service\RoleControlManagerInterface;
use Drupal\user\RoleInterface;

/**
 * Provides a role config elements.
 *
 * @RoleConfigElement(
 *   id = "account_form_mode",
 *   title = @Translation("Account form mode"),
 * )
 */
class AccountFormMode extends RoleConfigElementBase {

  /**
   * {@inheritdoc}
   */
  public function attachElement(&$form, RoleInterface $role) {
    $plugin_id = $this->getPluginId();
    $form['account'][$plugin_id] = [
      '#type' => 'select',
      '#title' => $this->t('Form mode'),
      '#options' => $this->getUserFormModesOptions(),
      '#description' => $this->t('Select which form mode to use on the user account edit form'),
      '#default_value' => $role->getThirdPartySetting(RoleControlManagerInterface::MODULE_NAME, $plugin_id),
    ];
  }

  /**
   * Get user form modes options.
   *
   * @return array
   *   Form mode options.
   */
  public function getUserFormModesOptions() {
    // Load user display modes.
    $user_form_modes = $this->entityDisplayRepository->getFormModeOptionsByBundle('user', 'user');
    $user_form_modes_options = ['default' => $this->t('Default')];
    foreach ($user_form_modes as $key => $form_mode_label) {
      $user_form_modes_options[$key] = $form_mode_label;
    }

    return $user_form_modes_options;
  }

}
