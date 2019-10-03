<?php

namespace Drupal\Tests\role\Unit;

use Drupal\Core\Cache\MemoryCache\MemoryCache;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\role\Plugin\RoleConfigElementManager;
use Drupal\role\Service\RoleControlManager;
use Drupal\Tests\UnitTestCase;
use Drupal\user\Entity\Role;

/**
 * Class RoleControlManager.
 *
 * @package Drupal\Tests\role\Unit
 *
 * @group role
 */
class RoleControlManagerTest extends UnitTestCase {

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
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * The mocked 'anonymous' user account.
   *
   * @var \Drupal\Core\Session\AccountInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $account1;

  /**
   * The mocked user with 'administrator' and 'authenticated' roles.
   *
   * @var \Drupal\Core\Session\AccountInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $account2;

  /**
   * The mocked user with 'authenticated' and 'administrator' roles.
   *
   * @var \Drupal\Core\Session\AccountInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $account3;

  /**
   * The mocked user with 'authenticated' 'administrator' 'editor' roles.
   *
   * @var \Drupal\Core\Session\AccountInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $account4;

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

    $this->roles['administrator'] = $this->getMockBuilder(Role::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->roles['authenticated'] = $this->getMockBuilder(Role::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->roles['editor'] = $this->getMockBuilder(Role::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->roles['anonymous'] = $this->getMockBuilder(Role::class)
      ->disableOriginalConstructor()
      ->getMock();

    $role_storage = $this->getMockBuilder('Drupal\user\RoleStorage')
      ->setConstructorArgs(['role', new MemoryCache()])
      ->disableOriginalConstructor()
      ->setMethods(['load'])
      ->getMock();

    $role_storage->expects($this->any())
      ->method('load')
      ->will($this->returnValueMap([
        ['administrator', $this->roles['administrator']],
        ['authenticated', $this->roles['authenticated']],
        ['editor', $this->roles['editor']],
        ['anonymous', $this->roles['anonymous']],
      ]));

    // Account 1: 'anonymous' role.
    $roles_1 = ['anonymous'];
    $this->account1 = $this->getMockBuilder(AccountInterface::class)->getMock();
    $this->account1->expects($this->any())
      ->method('isAnonymous')
      ->willReturn(TRUE);
    $this->account1->expects($this->any())
      ->method('getRoles')
      ->will($this->returnValue($roles_1));
    $this->account1->expects($this->any())
      ->method('id')
      ->willReturn(0);

    // Account 2: 'administrator' and 'authenticated' roles.
    $roles_2 = ['administrator', 'authenticated'];
    $this->account2 = $this->getMockBuilder(AccountInterface::class)->getMock();
    $this->account2->expects($this->any())
      ->method('getRoles')
      ->will($this->returnValue($roles_2));
    $this->account2->expects($this->any())
      ->method('id')
      ->willReturn(2);

    // Account 3: 'authenticated' and 'administrator' roles (different order).
    $roles_3 = ['authenticated', 'administrator'];
    $this->account3 = $this->getMockBuilder(AccountInterface::class)->getMock();
    $this->account3->expects($this->any())
      ->method('getRoles')
      ->will($this->returnValue($roles_3));
    $this->account3->expects($this->any())
      ->method('id')
      ->willReturn(3);

    // Account 4: 'authenticated' and 'administrator' roles (different order).
    $roles_4 = ['editor', 'authenticated', 'administrator'];
    $this->account4 = $this->getMockBuilder(AccountInterface::class)->getMock();
    $this->account4->expects($this->any())
      ->method('getRoles')
      ->will($this->returnValue($roles_4));
    $this->account4->expects($this->any())
      ->method('id')
      ->willReturn(4);

    $entity_manager = $this->getMockBuilder(EntityTypeManagerInterface::class)
      ->getMock();
    $entity_manager->expects($this->any())
      ->method('getStorage')
      ->with($this->equalTo('user_role'))
      ->will($this->returnValue($role_storage));

    $module_handler = $this->getMockBuilder(ModuleHandlerInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $roleConfigElementManager = $this->getMockBuilder(RoleConfigElementManager::class)
      ->disableOriginalConstructor()
      ->getMock();
    $roleConfigElementManager->expects($this->any())
      ->method('getDefinitions')
      ->will($this->returnValue($this->definitions));
    $entityDisplayRepository = $this->getMockBuilder(EntityDisplayRepositoryInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $translation_manager = $this->getMockBuilder(TranslationInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $roleManager = new RoleControlManager($entity_manager, $module_handler, $roleConfigElementManager, $entityDisplayRepository, $translation_manager);

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
  public function testGetExtraFields() {
    $fields = \Drupal::service('role.control_manager')->getExtraFields();
    foreach ($this->extraFields as $field_key) {
      $this->assertArrayHasKey($field_key, $fields);
    }
  }

  /**
   * Test fetching extra field keys.
   */
  public function testGetExtraFieldKey() {
    $role_control_manager = \Drupal::service('role.control_manager');
    foreach ($this->extraFields as $field_key) {
      $this->assertEquals($field_key, $role_control_manager->getExtraFieldKey($field_key));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function testGetUserPriority() {
    $role_control_manager = \Drupal::service('role.control_manager');

    $role_test_1 = $role_control_manager->getUserPriorityRole($this->account1);
    $this->assertEquals($role_test_1, $this->roles['anonymous']);

    $role_test_2 = $role_control_manager->getUserPriorityRole($this->account2);
    $this->assertEquals($role_test_2, $this->roles['administrator']);

    $role_test_3 = $role_control_manager->getUserPriorityRole($this->account3);
    $this->assertEquals($role_test_3, $this->roles['administrator']);

    $role_test_4 = $role_control_manager->getUserPriorityRole($this->account4);
    $this->assertEquals($role_test_4, $this->roles['editor']);

  }

}
