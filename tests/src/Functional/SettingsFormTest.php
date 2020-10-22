<?php

namespace Drupal\Tests\bcc\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test the module settings page
 *
 * @group bcc
 */
class SettingsPageTest extends BrowserTestBase {

  /**
   * The modules to load to run the test.
   *
   * @var array
   */
  public static $modules = [
      'user',
      'bcc',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
  }

  /**
   * Tests the setting form.
   */
  public function testForm() {
    // Create the user with the appropriate permission.
    $admin_user = $this->drupalCreateUser([
        'administer bcc settings',
    ]);

    // Start the session.
    $session = $this->assertSession();

    // Login as our account.
    $this->drupalLogin($admin_user);

    // Get the settings form path from the route
    $settings_form_path = Url::fromRoute('bcc.settings');

    // Navigate to the settings form
    $this->drupalGet($settings_form_path);

    // Assure we loaded settings with proper permissions.
    $session->statusCodeEquals(200);
  }

}