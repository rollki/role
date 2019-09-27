<?php

namespace Drupal\Tests\role\Functional;

use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityFormMode;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\Tests\BrowserTestBase;
use Drupal\user\Entity\Role;

/**
 * Tests set from mode pre role.
 *
 * @group user
 */
class RoleFormModeTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['field', 'role'];

  /**
   * Tests the role settings elements.
   */
  public function testFromModePerRole() {
    // Create form display and add custom field to it.
    $field_name = mb_strtolower($this->randomMachineName(8));
    $field_storage = FieldStorageConfig::create([
      'field_name' => $field_name,
      'entity_type' => 'user',
      'type' => 'string',
    ]);
    $field_storage->save();

    $instance = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'user',
      'label' => 'Test field',
    ]);
    $instance->save();

    $mode = mb_strtolower($this->randomMachineName());
    EntityFormMode::create([
      'id' => "user.$mode",
      'targetEntityType' => 'user',
      'label' => $mode,
    ])->save();

    EntityFormDisplay::create([
      'targetEntityType' => 'user',
      'bundle' => 'user',
      'mode' => $mode,
      'status' => TRUE,
    ])->setComponent($field_name, [
      'type' => 'string_textfield',
    ])->save();

    // Create role and set custom form mode.
    $role_1 = $this->drupalCreateRole([], 'custom_role_1', 'custom_role_1');
    $role = Role::load($role_1);
    $role->setThirdPartySetting('role', 'account_form_mode', $mode)->save();
    $this->assertTrue($role->getThirdPartySetting('role', 'account_form_mode'), 'Form mode setting was saved.');

    // Create user with custom role.
    $account = $this->drupalCreateUser();
    $account->addRole($role_1);
    $account->save();
    $this->drupalLogin($account);
    $this->drupalGet('/user/' . $account->id() . '/edit');
    // Check if field was added to custom form display and text is displayed.
    $this->assertFieldByName("{$field_name}[0][value]", '', 'Test field is displayed');

    // Create user without custom role.
    $account = $this->drupalCreateUser();
    $account->save();
    $this->drupalLogin($account);
    $this->drupalGet('/user/' . $account->id() . '/edit');
    // Check if field does not display.
    $this->assertNoFieldByName("{$field_name}[0][value]", '', 'Test field is not displayed');
  }

}
