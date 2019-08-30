<?php

namespace Drupal\role\Service;

use Drupal\user\UserInterface;

/**
 * Class RoleControlManager.
 */
class RoleControlManager implements RoleControlManagerInterface {

  /**
   * {@inheritdoc}
   */
  public function getExtraFields() {
    return [
      'account_form_mode' => 'account_form_mode',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getExtraFieldKey(string $name) {
    $fields = $this->getExtraFields();

    return $fields[$name];
  }

  /**
   * {@inheritdoc}
   */
  public function getUserFormMode(UserInterface $user) {
    // TODO implement user form mode negotiation,
    // which determine which role to use as main for user and fetch it settings.
    return '';
  }

}
