<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Step definitions for local_navbarhelpmenu.
 *
 * @package   local_navbarhelpmenu
 * @category  test
 * @copyright The Regents of the University of California
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;


/**
 * Step definitions for local_navbarhelpmenu.
 *
 * @package   local_navbarhelpmenu
 * @category  test
 * @copyright The Regents of the University of California
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_local_navbarhelpmenu extends behat_base {
    /**
     * Asserts that the help menu is present in the nav bar.
     *
     * @Then /^I should see the help menu in the navbar$/
     */
    public function i_should_see_the_help_menu_in_the_navbar(): void {
        $xpath = '//div[@id="usernavigation"]';
        $xpath .= '/div[contains(@class, "local-navbarhelpmenu")]';

        $this->execute("behat_general::should_exist", [$xpath, "xpath_element"]);
    }

    /**
     * Asserts that the help menu is present in the nav bar.
     *
     * @Then /^I shouldn't see the help menu in the navbar$/
     */
    public function i_shouldnt_see_the_help_menu_in_the_navbar(): void {
        $xpath = '//div[@id="usernavigation"]';
        $xpath .= '/div[contains(@class, "local-navbarhelpmenu")]';

        $this->execute("behat_general::should_not_exist", [$xpath, "xpath_element"]);
    }

    /**
     * Asserts that the help menu items are not visible.
     *
     * @Then /^help menu items shouldn't be visible$/
     */
    public function help_menu_items_shouldnt_be_visible(): void {
        $xpath = '//div[@id="usernavigation"]';
        $xpath .= '/div[contains(@class, "local-navbarhelpmenu")]';
        $xpath .= '/div[contains(@class, "dropdown-menu")]';

        $this->execute("behat_general::should_not_be_visible", [$xpath, "xpath_element"]);
    }

    /**
     * Asserts that the help menu items are not visible.
     *
     * @Then /^help menu items should be visible$/
     */
    public function help_menu_items_should_be_visible(): void {
        $xpath = '//div[@id="usernavigation"]';
        $xpath .= '/div[contains(@class, "local-navbarhelpmenu")]';
        $xpath .= '/div[contains(@class, "dropdown-menu")]';

        $this->execute("behat_general::should_be_visible", [$xpath, "xpath_element"]);
    }

    /**
     * Clicks on the help menu toggle to expand or collapse the menu.
     *
     * @Given /^I click on the help menu toggle$/
     */
    public function i_click_on_the_help_menu_toggle(): void {
        $this->execute(
            'behat_general::i_click_on_in_the',
            ['.dropdown-toggle', 'css_element', '.local-navbarhelpmenu', 'css_element']
        );
    }

    /**
     * Asserts that the help menu contains a given number of items.
     *
     * @Then /^I should see (\d+) help menu items?$/
     *
     * @param string $expectedcount The expected number of help menu items.
     * @throws ExpectationException
     */
    public function i_should_see_x_help_menu_items(string $expectedcount): void {
        $xpath = '//div[@id="usernavigation"]';
        $xpath .= '/div[contains(@class, "local-navbarhelpmenu")]';
        $xpath .= '/div[contains(@class, "dropdown-menu")]';
        $xpath .= '/a[contains(@class, "dropdown-item")]';
        try {
            $elements = $this->find_all('xpath', $xpath);
        } catch (ElementNotFoundException) {
            $elements = [];
        }
        $count = count($elements);
        if ($count !== (int) $expectedcount) {
            throw new ExpectationException(
                "Found $count menu items in navbar help menu. Expected $expectedcount.",
                $this->getSession()->getDriver()
            );
        }
    }

    /**
     * Asserts that a help menu item in a given position has the given title and link,
     * with the link targeting this window.
     *
     * @Then /^I should see help menu item (\d+) with title "([^"]*)" linking to "([^"]*)" in this window$/
     *
     * @param string $position The menu item position within the menu.
     * @param string $expectedtitle The expected title of the menu item.
     * @param string $expectedlink The expected link of the menu item.
     * @throws ExpectationException
     */
    public function i_should_see_help_menu_item_with_title_this_window(
        string $position,
        string $expectedtitle,
        string $expectedlink
    ): void {
        $this->assert_help_menu_item($position, $expectedtitle, $expectedlink, '_self');
    }

    /**
     * Asserts that a help menu item in a given position has the given title and link,
     * with the link targeting a new window.
     *
     * @Then /I should see help menu item (\d+) with title "([^"]*)" linking to "([^"]*)" in a new window$/
     *
     * @param string $position The menu item position within the menu.
     * @param string $expectedtitle The expected title of the menu item.
     * @param string $expectedlink The expected link of the menu item.
     * @throws ExpectationException
     */
    public function i_should_see_help_menu_item_with_title_new_window(
        string $position,
        string $expectedtitle,
        string $expectedlink
    ): void {
        $this->assert_help_menu_item($position, $expectedtitle, $expectedlink, '_blank');
    }

    /**
     * Asserts that a help menu item in a given position has the given title, link, and link target.
     *
     * @Then /I should see help menu item (\d+) with title "([^"]*)" linking to "([^"]*)" in a new window$/
     *
     * @param string $position The menu item position within the menu.
     * @param string $expectedtitle The expected title of the menu item.
     * @param string $expectedlink The expected link of the menu item.
     * @param string $expectedlinktarget The expected link target of the menu item.
     * @throws ExpectationException
     */
    protected function assert_help_menu_item(
        string $position,
        string $expectedtitle,
        string $expectedlink,
        string $expectedlinktarget
    ): void {

        $xpath = '//div[@id="usernavigation"]';
        $xpath .= '/div[contains(@class, "local-navbarhelpmenu")]';
        $xpath .= '/div[contains(@class, "dropdown-menu")]';
        $xpath .= "/a[$position]";

        $menuitem = $this->find('xpath', $xpath);

        $title = trim($menuitem->getText());
        $link = trim($menuitem->getAttribute('href'));
        $linktarget = trim($menuitem->getAttribute('target'));

        if ($title !== $expectedtitle) {
            throw new ExpectationException(
                "Menu item $position has title '$title'. Expected title '$expectedtitle'.",
                $this->getSession()->getDriver()
            );
        }

        if ($link !== $expectedlink) {
            throw new ExpectationException(
                "Menu item $position links to '$link'. Expected link '$expectedlink'.",
                $this->getSession()->getDriver()
            );
        }

        if ($linktarget !== $expectedlinktarget) {
            throw new ExpectationException(
                "Menu item $position link targets '$linktarget'. Expected linktarget '$expectedlinktarget'.",
                $this->getSession()->getDriver()
            );
        }
    }
}
