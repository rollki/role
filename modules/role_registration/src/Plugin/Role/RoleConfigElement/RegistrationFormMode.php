<?php

namespace Drupal\role_registration\Plugin\Role\RoleConfigElement;

use Drupal\role\Plugin\RoleConfigElementBase;
use Drupal\user\RoleInterface;

/**
 * Provides a role config element.
 *
 * @RoleConfigElement(
 *   id = "account_registration_form_mode",
 *   title = @Translation("Registration form mode"),
 * )
 */
class RegistrationFormMode extends RoleConfigElementBase {

  /**
   * {@inheritdoc}
   */
  public function attachElement(&$form, RoleInterface $role) {
    $plugin_id = $this->getPluginId();

    $form['account']['registration'][$plugin_id] = [
      '#type' => 'select',
      '#title' => $this->t('Registration Form mode'),
      '#options' => $this->roleControlManager->getUserFormModesOptions(),
      '#description' => $this->t('Select which form mode to use on the user registration page'),
      '#states' => [
        'visible' => [
          [':input[name="account_registration_status"]' => ['checked' => TRUE]],
        ],
      ],
      '#default_value' => $role->getThirdPartySetting($this->pluginDefinition['provider'], $plugin_id),
      '#weight' => 2,
    ];
  }

}
