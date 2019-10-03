<?php

namespace Drupal\role_registration\Service;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
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
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * RoleRegistrationManager constructor.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    EntityDisplayRepositoryInterface $entity_display_repository,
    AliasStorageInterface $alias_storage,
    LanguageManagerInterface $language_manager
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->entityDisplayRepository = $entity_display_repository;
    $this->aliasStorage = $alias_storage;
    $this->languageManager = $language_manager;
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
  public function cleanAlias($alias) {
    // Trim the submitted value of whitespace and slashes. Ensure to not trim
    // the slash on the left side.
    return $alias = rtrim(trim(trim($alias), ''), "\\/");
  }

  /**
   * {@inheritdoc}
   */
  public function isAliasExist($alias) {
    $langcode = $this->languageManager->getDefaultLanguage()->getId();
    return $this->aliasStorage->aliasExists($alias, $langcode);
  }

  /**
   * {@inheritdoc}
   */
  public function updateAlias($source, $alias) {
    // First we check if source has alias.
    $langcode = $this->languageManager->getDefaultLanguage()->getId();
    $lookupAlias = $this->aliasStorage->lookupPathAlias($source, $langcode);
    if ($lookupAlias) {
      // Delete old alias.
      $this->aliasStorage->delete([
        'source' => $source,
        'alias' => $lookupAlias,
      ]);
    }
    // Create new alias.
    $this->aliasStorage->save($source, $alias, $langcode);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteAliasBySource($source) {
    $this->aliasStorage->delete(['source' => $source]);
  }

}
