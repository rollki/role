<?php

namespace Drupal\role\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\user\RoleForm;

/**
 * Role form for the user role.
 */
class TotalRoleForm extends RoleForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $entity = $this->entity;

    $form['view_mode'] = [
      '#type' => 'select',
      '#title' => $this->t('View mode'),
      '#options' => [],
      '#default' => '',
    ];

    return $form;
  }

}
