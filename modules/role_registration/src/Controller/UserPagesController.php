<?php

namespace Drupal\role_registration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\role_registration\Service\RoleRegistrationManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserPagesController.
 *
 * @package Drupal\role_registration\Controller
 */
class UserPagesController extends ControllerBase {

  /**
   * The RoleRegistrationManager service.
   *
   * @var \Drupal\role_registration\Service\RoleRegistrationManager
   */
  protected $roleRegistrationManager;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    RoleRegistrationManager $role_registration_manager,
    EntityTypeManagerInterface $entityTypeManager,
    FormBuilderInterface $formBuilder
  ) {
    $this->roleRegistrationManager = $role_registration_manager;
    $this->entityTypeManager = $entityTypeManager;
    $this->formBuilder = $formBuilder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('role_registration.manager'),
      $container->get('entity_type.manager'),
      $container->get('form_builder')
    );
  }

  /**
   * @param \Drupal\role_registration\Controller\string $role_id
   *
   * @return array
   */
  public function registerPage($role_id) {
    $form_display = $this->roleRegistrationManager->getUserRegistrationFormMode($role_id);
    // Be sure definition is up to date.
    /** @var \Drupal\Core\Entity\ContentEntityType $userEntityDefinition */
    $userEntityDefinition = $this->entityTypeManager->getDefinition('user');
    if (!array_key_exists($form_display, $userEntityDefinition->getHandlerClasses()['form'])) {
      $this->entityTypeManager->clearCachedDefinitions();
    }

    return [];
  }

}
