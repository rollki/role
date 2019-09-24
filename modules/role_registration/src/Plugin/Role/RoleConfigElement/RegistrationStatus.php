<?php

namespace Drupal\role_registration\Plugin\Role\RoleConfigElement;

use Drupal\role\Plugin\RoleConfigElementBase;
use Drupal\user\RoleInterface;

/**
 * Provides a role config element.
 *
 * @RoleConfigElement(
 *   id = "registration_status",
 *   title = @Translation("Registration status"),
 * )
 */
class RegistrationStatus extends RoleConfigElementBase {

  /**
   * {@inheritdoc}
   */
  public function attachElement(&$form, RoleInterface $role) {
    $plugin_id = $this->getPluginId();

    $form['account']['registration'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Registration'),
    ];

    $form['account']['registration'][$plugin_id] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable registration by form mode'),
      '#default_value' => $role->getThirdPartySetting($this->pluginDefinition['provider'], $plugin_id),
    ];
  }

}
