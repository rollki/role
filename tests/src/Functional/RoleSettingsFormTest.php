<?php

namespace Drupal\Tests\role\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the role settings form UI.
 *
 * @group user
 */
class RoleSettingsFormTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'role',
  ];


  /**
   * User with 'administer menu' and 'link to any page' permission.
   *
   * @var \Drupal\user\Entity\User
   */

  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->adminUser = $this->drupalCreateUser(['administer permissions', 'administer users']);
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Tests the role settings elements.
   */
  public function testRoleFormSettings() {
    $this->drupalGet('admin/people/roles/add');
    $form_mode_element = $this->xpath('//select[@name = :name]', [':name' => 'account_form_mode']);
    $this->assertTrue($form_mode_element, 'Form mode setting was found.');
    $view_mode_element = $this->xpath('//select[@id = :id]', [':id' => 'edit-account-view-mode']);
    $this->assertTrue($view_mode_element, 'View mode setting was found.');

    $role_name = 'total_role';
    $edit = [
      'label' => $role_name,
      'id' => $role_name,
      'account_form_mode' => 'default',
      'account_view_mode' => 'default',
    ];

    $this->drupalPostForm(NULL, $edit, t('Save'));
    $this->assertText(t('Role @role has been added.', ['@role' => $role_name]));
  }

}
