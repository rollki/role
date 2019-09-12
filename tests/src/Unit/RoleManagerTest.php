<?php

namespace Drupal\Tests\role\Unit;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Entity\EntityTypeManagerInterface;
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
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $container = new ContainerBuilder();
    $this->extraFields = ['account_form_mode', 'account_view_mode'];
    $entityTypeManager = $this->getMockBuilder(EntityTypeManagerInterface::class)
      ->disableOriginalConstructor()
      ->getMock();
    $roleManager = new RoleControlManager($entityTypeManager);
    \Drupal::setContainer($container);
    $container->set('role.control_manager', $roleManager);
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
