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

namespace local_navbarhelpmenu\output;

use dml_exception;
use local_navbarhelpmenu\constants;
use renderable;
use renderer_base;
use stdClass;
use templatable;

/**
 * Local plugin "Navbar Help Menu" - Renderable help menu component.
 *
 * @package local_navbarhelpmenu
 * @copyright The Regents of the University of California
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helpmenu implements renderable, templatable {
    /**
     * Retrieve menu items.
     *
     * @param renderer_base $output
     * @return stdClass
     * @throws dml_exception
     */
    public function export_for_template(renderer_base $output): stdClass {
        $menu = new stdClass();
        $menu->items = [];
        $config = get_config('local_navbarhelpmenu');

        if (isset($config->menuitems)) {
            $lines = explode(PHP_EOL, $config->menuitems);
            foreach ($lines as $line) {
                $line = trim($line);
                if ('' === $line) {
                    continue;
                }

                $settings = explode('|', $line);
                if (count($settings) >= 2 && count($settings) <= 3) {
                    $url = trim($settings[0]);
                    $title = trim($settings[1]);
                    $newwindow = 3 === count($settings) && 'true' === trim($settings[2]);
                    if ('' !== $url && '' !== $title) {
                        $menu->items[] = [
                            'url' => $url,
                            'title' => $title,
                            'target' => $newwindow ? '_blank' : '_self',
                        ];
                    }
                }
            }
        }

        $menu->hasitems = !empty($menu->items);
        return $menu;
    }
}
