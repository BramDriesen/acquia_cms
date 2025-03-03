<?php

namespace Drupal\Tests\acquia_cms\ExistingSiteJavascript;

/**
 * Test that "Google map" component is installed and operating correctly.
 *
 * @group acquia_cms
 * @group site_studio
 * @group low_risk
 * @group pr
 * @group push
 */
class GoogleMapComponentTest extends CohesionComponentTestBase {

  /**
   * Tests that the component can be added to a layout canvas.
   */
  public function testComponent(): void {
    $account = $this->createUser();
    $account->addRole('administrator');
    $account->save();
    $this->drupalLogin($account);

    $this->drupalGet('/node/add/page');

    // Add the component to the layout canvas.
    /** @var \Behat\Mink\Element\TraversableElement $edit_form */
    $edit_form = $this->getLayoutCanvas()->add('Google map')->edit();
    $this->assertSession()->elementExists('xpath', '//button[span[text()="Map marker"]]')->click();

    $edit_form->fillField('Address', 'Test Address');
    $edit_form->fillField('Latitude', '22.52138');
    $edit_form->fillField('Longitude', '88.294324');

    $this->getSession()->getPage()->clickLink('Layout and style');

    $this->assertSession()->optionExists('Space below map', 'Add space below map', $edit_form);
  }

  /**
   * Tests that component can be edited by a specific user role.
   *
   * @param string $role
   *   The ID of the user role to test with.
   *
   * @dataProvider providerEditAccess
   */
  public function testEditAccess(string $role): void {
    $account = $this->createUser();
    $account->addRole($role);
    $account->save();
    $this->drupalLogin($account);

    $this->drupalGet('/admin/cohesion/components/components');
    $this->editDefinition('Map components', 'Google map');
  }

}
