<?php

namespace Drupal\role_registration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormState;
use Drupal\role_registration\Service\RoleRegistrationManager;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    if (!$form_display) {
      throw new NotFoundHttpException();
    }
    // Be sure definition is up to date.
    /** @var \Drupal\Core\Entity\ContentEntityType $userEntityDefinition */
    $userEntityDefinition = $this->entityTypeManager->getDefinition('user');
    if (!array_key_exists($form_display, $userEntityDefinition->getHandlerClasses()['form'])) {
      $this->entityTypeManager->clearCachedDefinitions();
    }

    $entity = User::create();
    $form_object = $this->entityTypeManager->getFormObject($entity->getEntityTypeId(), $form_display);
    $form_object->setEntity($entity);

    $form_state = (new FormState())->setFormState([]);
    // Add role id value for form state.
    $form_state->setValue('roleId', $role_id);
    $registerForm = $this->formBuilder->buildForm($form_object, $form_state);

    return $registerForm;
  }

}
