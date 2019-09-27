<?php

namespace Drupal\role_registration\Service;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Path\AliasStorageInterface;

/**
 * Class RoleRegistrationManager.
 */
class RoleRegistrationManager implements RoleRegistrationManagerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * The alias storage service.
   *
   * @var \Drupal\Core\Path\AliasStorage
   */
  protected $aliasStorage;

  /**
   * RoleRegistrationManager constructor.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    EntityDisplayRepositoryInterface $entity_display_repository,
    AliasStorageInterface $alias_storage
  ) {
   $this->entityTypeManager = $entity_type_manager;
    $this->entityDisplayRepository = $entity_display_repository;
    $this->aliasStorage = $alias_storage;
  }

  /**
   * {@inheritdoc}
   */
  public function getRegisterDisplayBasePath() {
    return self::ROLE_REGISTRATION_BASE_REGISTER_PATH;
  }

  /**
   * {@inheritdoc}
   */
  public static function addRoleToUser(array &$form, FormStateInterface $form_state) {
    $role_id = $form_state->getValue('role_id');
    $form_state->setValue(['roles', $role_id], $role_id);
  }

  /**
   * {@inheritdoc}
   */
  public function isAliasExist($alias) {
    return $this->aliasStorage->aliasExists($alias, LanguageInterface::LANGCODE_NOT_SPECIFIED);
  }

  /**
   * {@inheritdoc}
   */
  public function updateAlias($source, $alias) {
    // First we check if source has alias.
    $lookupAlias = $this->aliasStorage->lookupPathAlias($source, LanguageInterface::LANGCODE_NOT_SPECIFIED);
    if ($lookupAlias) {
      // Delete old alias.
      $this->aliasStorage->delete([
        'source' => $source,
        'alias' => $lookupAlias,
      ]);
    }
    // Create new alias.
    $this->aliasStorage->save($source, $alias, LanguageInterface::LANGCODE_NOT_SPECIFIED);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteAliasBySource($source) {
    $this->aliasStorage->delete(['source' => $source]);
  }
}
