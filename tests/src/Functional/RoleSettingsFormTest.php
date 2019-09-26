<?php

namespace Drupal\Tests\role\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\user\Entity\Role;

/**
 * Tests adding, editing and deleting user roles and changing role weights.
 *
 * @group user
 */
class RoleSettingsFormTest extends BrowserTestBase {

  /**
   * User with admin privileges.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['role'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->adminUser = $this->drupalCreateUser(['administer permissions', 'administer users']);
  }

  /**
   * Tests the role settings elements.
   */
  public function testRoleSettingsForm() {
    $this->drupalLogin($this->adminUser);

    $this->drupalGet('admin/people/roles/add');
    $form_mode_element = $this->xpath('//select[@name=:name]', [':name' => 'account_form_mode']);
    $this->assertTrue(isset($form_mode_element), 'Form mode setting was found.');
    $view_mode_element = $this->xpath('//select[@id=:id]', [':id' => 'edit-account-view-mode']);
    $this->assertTrue(isset($view_mode_element), 'View mode setting was found.');

    $role_name = 'total_role';
    $edit = [
      'label' => $role_name,
      'id' => $role_name,
      'account_form_mode' => 'default',
      'account_view_mode' => 'default',
    ];
    $this->drupalPostForm('admin/people/roles/add', $edit, t('Save'));
    $this->assertRaw(t('Role %label has been added.', ['%label' => $role_name]));
    $role = Role::load($role_name);
    $this->assertTrue(is_object($role), 'The role was successfully retrieved from the database.');

    $this->assertTrue(!empty($role->getThirdPartySetting('role', 'account_form_mode')), 'Form mode setting was saved.');
    $this->assertTrue(!empty($role->getThirdPartySetting('role', 'account_view_mode')), 'View mode setting was saved.');
  }

}
