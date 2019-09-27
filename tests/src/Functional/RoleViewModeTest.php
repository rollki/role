<?php

namespace Drupal\Tests\role\Functional;

use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\Entity\EntityViewMode;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\Tests\BrowserTestBase;
use Drupal\user\Entity\Role;

/**
 * Tests set view mode pre role.
 *
 * @group user
 */
class RoleViewModeTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['field', 'role'];

  /**
   * Tests the role settings elements.
   */
  public function testViewModePerRole() {
    // Create view display and add custom field to it.
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
    EntityViewMode::create([
      'id' => "user.$mode",
      'targetEntityType' => 'user',
      'label' => $mode,
    ])->save();

    EntityViewDisplay::create([
      'targetEntityType' => 'user',
      'bundle' => 'user',
      'mode' => $mode,
      'status' => TRUE,
    ])->setComponent($field_name, [
      'type' => 'string',
    ])->save();
    // Creatr role and set custom view display.
    $role_1 = $this->drupalCreateRole([], 'custom_role_1', 'custom_role_1');
    $role = Role::load($role_1);
    $role->setThirdPartySetting('role', 'account_view_mode', $mode)->save();
    $this->assertTrue($role->getThirdPartySetting('role', 'account_view_mode'), 'View mode setting was saved.');
    // Create user with custom role.
    $account = $this->drupalCreateUser();
    $account->addRole($role_1);
    $account->$field_name = $mode;
    $account->save();
    $this->drupalLogin($account);
    $this->drupalGet('user/' . $account->id());
    // Check if field was added to custom view display and text is displayed.
    $this->assertText($mode);
    // Create user without custom role.
    $account = $this->drupalCreateUser();
    $account->$field_name = $mode;
    $account->save();
    $this->drupalLogin($account);
    $this->drupalGet('user/' . $account->id());
    // Check if field does not display.
    $this->assertNoText($mode);
  }

}
