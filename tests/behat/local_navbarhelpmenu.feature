@local @local_navbarhelpmenu
Feature: Configuring the navbarhelpmenu plugin
  In order to have custom help menu in the navbar
  As admin
  I need to be able to configure the navbarhelpmenu plugin

  Scenario: Configuring the help menu without items
    When I log in as "admin"
    And I navigate to "Appearance > Navbar Help Menu" in site administration
    And I set the field "id_s_local_navbarhelpmenu_menuitems" to ""
    And I press "Save"
    Then I shouldn't see the help menu in the navbar

  Scenario: Configuring the help menu without valid items
    When I log in as "admin"
    And I navigate to "Appearance > Navbar Help Menu" in site administration
    And I set the field "id_s_local_navbarhelpmenu_menuitems" to multiline:
    """
    https://link-but-no-title.example|
    |Title but no link
    """
    And I press "Save"
    Then I shouldn't see the help menu in the navbar

  @javascript
  Scenario: Expand and collapse help menu
    When I log in as "admin"
    And I navigate to "Appearance > Navbar Help Menu" in site administration
    And I set the field "id_s_local_navbarhelpmenu_menuitems" to "https://moodle.org|Moodle"
    And I press "Save"
    Then I should see the help menu in the navbar
    But help menu items shouldn't be visible
    When I click on the help menu toggle
    Then help menu items should be visible
    When I click on the help menu toggle
    Then help menu items shouldn't be visible

  @javascript @accessibility
  Scenario: The help menu is accessible
    When I log in as "admin"
    And I navigate to "Appearance > Navbar Help Menu" in site administration
    And I set the field "id_s_local_navbarhelpmenu_menuitems" to multiline:
    """
    https://moodle.org|Moodle|true
    /user/contactsitesupport.php|Site Support
    """
    And I press "Save"
    Then I should see the help menu in the navbar
    And the ".local-navbarhelpmenu" "css_element" should meet accessibility standards with 'best-practice' extra tests
    When I click on the help menu toggle
    Then I should see 2 help menu items
    And the ".local-navbarhelpmenu" "css_element" should meet accessibility standards with 'best-practice' extra tests

  Scenario: Configuring the help menu with valid items
    When I log in as "admin"
    And I navigate to "Appearance > Navbar Help Menu" in site administration
    And I set the field "id_s_local_navbarhelpmenu_menuitems" to multiline:
    """
    https://moodle.org|Moodle|true
    /user/contactsitesupport.php|Site Support
    """
    And I press "Save"
    Then I should see the help menu in the navbar
    And I should see 2 help menu items
    And I should see help menu item 1 with title "Moodle" linking to "https://moodle.org" in a new window
    And I should see help menu item 2 with title "Site Support" linking to "/user/contactsitesupport.php" in this window

  Scenario: Configuring the help menu with a mix of valid and invalid items
    When I log in as "admin"
    And I navigate to "Appearance > Navbar Help Menu" in site administration
    And I set the field "id_s_local_navbarhelpmenu_menuitems" to multiline:
    """
    https://moodle.org|Moodle|true
    https://link-but-no-title.example|
    |Title but no link
    /foo/bar||true

    /user/contactsitesupport.php|Site Support|
    """
    And I press "Save"
    Then I should see the help menu in the navbar
    And I should see 2 help menu items
    And I should see help menu item 1 with title "Moodle" linking to "https://moodle.org" in a new window
    And I should see help menu item 2 with title "Site Support" linking to "/user/contactsitesupport.php" in this window
