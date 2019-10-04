<?php

namespace Drupal\Tests\role_appearance\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\user\Entity\Role;

/**
 * Tests set theme per role.
 *
 * @group user
 */
class RoleThemeTest extends BrowserTestBase {

  /**
   * User with admin privileges.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * The theme handler used in this test for enabling themes.
   *
   * @var \Drupal\Core\Extension\ThemeHandler
   */
  protected $themeHandler;

  /**
   * The theme negotiator used in this test for checking active theme.
   *
   * @var \Drupal\role_appearance\Theme\ThemeNegotiator
   */
  protected $themeNegotiator;


  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['role', 'role_appearance'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->adminUser = $this->drupalCreateUser(['administer permissions', 'administer users']);
    $this->themeHandler = $this->container->get('theme_handler');
    $this->themeNegotiator = $this->container->get('theme.negotiator.role_appearance');
  }

  /**
   * Tests the role theme settings.
   */
  public function testaRoleThemeSettings() {
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('admin/people/roles/add');
    $theme_element = $this->xpath('//select[@name=:name]', [':name' => 'role_theme']);
    $this->assertTrue($theme_element, 'Theme setting was found.');

    $role_name = 'total_role';
    $edit = [
      'label' => $role_name,
      'id' => $role_name,
      'role_theme' => 'seven',
    ];
    $this->drupalPostForm('admin/people/roles/add', $edit, t('Save'));
    $this->assertRaw(t('Role %label has been added.', ['%label' => $role_name]));
    $role = Role::load($role_name);
    $this->assertTrue(is_object($role), 'The role was successfully retrieved from the database.');
    $role_theme = $role->getThirdPartySetting('role_appearance', 'role_theme');
    $this->assertTrue(!empty($role_theme), 'Theme setting was saved.');
    $this->assertEquals($role_theme, 'seven');
  }

  /**
   * Tests the role theme negotiator.
   */
  public function testDetermineActiveTheme() {
    $role_theme = 'seven';
    // Creatr role and set custom view display.
    $role_1 = $this->drupalCreateRole([], 'custom_role_1', 'custom_role_1');
    $role = Role::load($role_1);
    $role->setThirdPartySetting('role_appearance', 'role_theme', $role_theme)->save();
    $this->assertTrue($role->getThirdPartySetting('role_appearance', 'role_theme'), 'Theme setting was saved.');

    // Install and set default theme.
    $this->themeHandler->install(['bartik']);
    $this->config('system.theme')->set('default', 'bartik')->save();

    // Create account without theme per role settings.
    $account = $this->drupalCreateUser();
    $this->drupalLogin($account);

    $route_name = \Drupal::routeMatch();
    $role_theme = $this->themeNegotiator->determineActiveTheme($route_name);
    $this->assertEquals($role_theme, 'bartik');

    // Create user with custom role.
    $account = $this->drupalCreateUser();
    $account->addRole($role_1);
    $account->save();
    $this->drupalLogin($account);

    $route_name = \Drupal::routeMatch();
    $role_theme = $this->themeNegotiator->determineActiveTheme($route_name);
    $this->assertEquals($role_theme, 'seven');
  }

}
