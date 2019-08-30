<?php

namespace Drupal\role\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\RoleForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Role form for the user role.
 */
class TotalRoleForm extends RoleForm implements ContainerInjectionInterface {

  /**
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * TotalRoleForm constructor.
   *
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface|NULL $entity_display_repository
   */
  public function __construct(EntityDisplayRepositoryInterface $entity_display_repository = NULL) {
    $this->entityDisplayRepository = $entity_display_repository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_display.repository')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var \Drupal\user\Entity\Role $entity */
    $entity = $this->entity;

    // Load user display modes.
    $user_form_modes = $this->entityDisplayRepository->getFormModes('user');
    $user_form_modes_options = ['default' => $this->t('Default')];
    foreach ($user_form_modes as $key => $form_mode) {
      $user_form_modes_options[$key] = $form_mode['label'];
    }

    $form['form_mode'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Account edit page'),
    ];

    $form['form_mode']['display'] = [
      '#type' => 'select',
      '#title' => $this->t('Form mode'),
      '#options' => $user_form_modes_options,
      '#default_value' => $entity->getThirdPartySetting('role', 'form_mode'),
    ];

    return $form;
  }

}
