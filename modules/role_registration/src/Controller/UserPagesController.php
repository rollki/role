<?php

namespace Drupal\role_registration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormState;
use Drupal\role_registration\Service\RoleRegistrationManagerInterface;
use Drupal\user\RoleStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UserPagesController.
 *
 * @package Drupal\role_registration\Controller
 */
class UserPagesController extends ControllerBase {

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
   * The role storage used when changing the admin role.
   *
   * @var \Drupal\user\RoleStorageInterface
   */
  protected $roleStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    FormBuilderInterface $formBuilder,
    RoleStorageInterface $role_storage
  ) {
    $this->entityTypeManager = $entityTypeManager;
    $this->formBuilder = $formBuilder;
    $this->roleStorage = $role_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('form_builder'),
      $container->get('entity.manager')->getStorage('user_role')
    );
  }

  /**
   * Builds the registration form.
   *
   * @param string $role_id
   *   The role_id parameter for registration form.
   *
   * @return array
   *   The register form.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Form\EnforcedResponseException
   * @throws \Drupal\Core\Form\FormAjaxException
   */
  public function registerPage(string $role_id) {
    /** @var \Drupal\user\RoleInterface $role */
    $role = $this->roleStorage->load($role_id);
    $third_party_settings = $role->getThirdPartySettings(RoleRegistrationManagerInterface::MODULE_NAME);
    if (!$third_party_settings['account_registration_status']) {
      throw new NotFoundHttpException();
    }
    $form_display = $third_party_settings['account_registration_form_mode'];
    // Be sure definition is up to date.
    /** @var \Drupal\Core\Entity\ContentEntityType $userEntityDefinition */
    $userEntityDefinition = $this->entityTypeManager->getDefinition('user');
    if (!array_key_exists($form_display, $userEntityDefinition->getHandlerClasses()['form'])) {
      $this->entityTypeManager->clearCachedDefinitions();
    }

    $entity = $this->entityTypeManager()->getStorage('user')->create();
    $form_object = $this->entityTypeManager->getFormObject($entity->getEntityTypeId(), $form_display);
    $form_object->setEntity($entity);

    $form_state = (new FormState())->setFormState([]);
    // Add role id value for form state.
    $form_state->setValue('role_id', $role_id);
    $registerForm = $this->formBuilder->buildForm($form_object, $form_state);

    return $registerForm;
  }

}
