<?php

namespace Drupal\Tests\role\Unit;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\role\Plugin\RoleConfigElementManager;
use Drupal\role\Service\RoleControlManager;
use Drupal\Tests\UnitTestCase;

/**
 * Class RoleManagerTest.
 *
 * @package Drupal\Tests\role\Unit
 *
 * @group role
 */
class RoleManagerTest extends UnitTestCase {

  /**
   * Extra fields keys.
   *
   * @var array
   */
  protected $extraFields;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface|
   */
  protected $moduleHandler;

  /**
   * Drupal\role\Plugin\RoleConfigElementManager definition.
   *
   * @var \Drupal\role\Plugin\RoleConfigElementManager
   */
  protected $roleConfigElementManager;

  /**
   * A list of role plugin definitions.
   *
   * @var array
   */
  protected $definitions = [
    'account_form_mode' => [
      'id' => 'account_form_mode',
      'class' => 'Drupal\role\Plugin\Role\RoleConfigElement\AccountFormMode',
    ],
    'account_view_mode' => [
      'id' => 'account_view_mode',
      'class' => 'Drupal\role\Plugin\Role\RoleConfigElement\AccountViewMode',
    ],
  ];


  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $container = new ContainerBuilder();

    $this->extraFields = ['account_form_mode', 'account_view_mode'];

    $entityTypeManager = $this->getMockBuilder(EntityTypeManagerInterface::class)
      ->disableOriginalConstructor()
      ->getMock();
    $module_handler = $this->getMockBuilder(ModuleHandlerInterface::class)
      ->disableOriginalConstructor()
      ->getMock();
    $roleConfigElementManager = $this->getMockBuilder(RoleConfigElementManager::class)
      ->disableOriginalConstructor()
      ->getMock();
    $roleConfigElementManager->expects($this->any())
      ->method('getDefinitions')
      ->will($this->returnValue($this->definitions));

    $roleManager = new RoleControlManager($entityTypeManager, $module_handler, $roleConfigElementManager);

    $container->set('role.control_manager', $roleManager);
    \Drupal::setContainer($container);

  }

  /**
   * Checks if the service is created in the Drupal context.
   */
  public function testRoleControlManager() {
    $this->assertNotNull(\Drupal::service('role.control_manager'));
  }

  /**
   * Test if extra field keys are correct.
   */
  public function testExtraFieldsNames() {
    $fields = \Drupal::service('role.control_manager')->getExtraFields();
    foreach ($this->extraFields as $field_key) {
      $this->assertArrayHasKey($field_key, $fields);
    }
  }

  /**
   * Test fetching extra field keys.
   */
  public function testGettingExtraFieldKey() {
    foreach ($this->extraFields as $field_key) {
      $this->assertEquals($field_key, \Drupal::service('role.control_manager')->getExtraFieldKey($field_key));
    }
  }

}
