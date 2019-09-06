<?php

namespace Drupal\role\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Role Single configuration settings.
 */
class SingleRoleSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'single_role_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['single.role.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('single.role.settings');

    $form['single_role'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Single role'),
    ];
    $form['single_role']['state'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable single role functionality'),
      '#default_value' => $config->get('state'),
    ];
    $form['single_role']['field_type'] = [
      '#title' => t('Field type'),
      '#description' => $this->t('Set type of field to use for user role selection.'),
      '#type' => 'select',
      '#default_value' => $config->get('field_type'),
      '#options' => [
        'select' => t('Select field'),
        'radios' => t('Radio field'),
      ],
      '#states' => [
        'visible' => [
          [':input[name="state"]' => ['checked' => TRUE]],
        ],
      ],
    ];
    $form['single_role']['field_description'] = [
      '#title' => 'Role field help text',
      '#type' => 'textarea',
      '#description' => $this->t('This text is displayed at user role field.'),
      '#default_value' => $config->get('field_description'),
      '#cols' => 40,
      '#rows' => 4,
      '#states' => [
        'visible' => [
          [':input[name="state"]' => ['checked' => TRUE]],
        ],
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('single.role.settings');

    foreach ($form_state->getValues() as $key => $variable) {
      if (!is_array($key)) {
        $config->set($key, $form_state->getValue($key));
      }
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

}
